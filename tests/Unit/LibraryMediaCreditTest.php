<?php

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Functions\when;

it('pins media-credit editor fields, stored overrides and metadata fallbacks', function ($saved, string $embedded, $metadata, string $value, string $help): void {
    Functions\expect('get_attached_file')->once()->with(41)->andReturn('/image.jpg');
    Functions\expect('wp_read_image_metadata')->once()->with('/image.jpg')->andReturn(['credit' => $embedded]);
    Functions\expect('get_post_meta')->once()->with(41, '_media_credit', true)->andReturn($saved);
    when('wp_get_attachment_metadata')->justReturn($metadata);
    when('esc_attr')->alias(static fn ($text): string => 'ATTR:'.$text);
    when('esc_html')->alias(static fn ($text): string => 'HTML:'.$text);

    expect(uamswp_attachment_fields_to_edit_source(['other' => 'keep'], (object) ['ID' => 41]))->toBe([
        'other' => 'keep', 'media_credit' => ['label' => 'ATTR:Image Credit', 'input' => 'text', 'value' => 'ATTR:'.$value, 'helps' => 'Original Credit: HTML:'.$help],
    ]);
})->with([
    ['Saved', 'Embedded', ['image_meta' => ['credit' => 'Metadata']], 'Saved', 'Embedded'],
    [null, 'Embedded', false, 'Embedded', 'Embedded'],
    ['', '', ['image_meta' => ['credit' => 'Metadata']], '', 'Metadata'],
    ['', '', false, '', ''], ['', '', ['image_meta' => 'not array'], '', ''],
]);

it('updates media credits only when a submitted field differs', function (array $submitted, $saved, string $action): void {
    $post = ['ID' => 41, 'other' => 'keep'];
    Functions\expect('get_post_meta')->times(isset($submitted['media_credit']) ? 1 : 0)->with(41, '_media_credit', true)->andReturn($saved);
    Functions\expect('delete_post_meta')->times($action === 'delete' ? 1 : 0)->with(41, '_media_credit');
    Functions\expect('update_post_meta')->times($action === 'update' ? 1 : 0)->with(41, '_media_credit', $submitted['media_credit'] ?? '');
    expect(uamswp_attachment_fields_to_save_source_url_name($post, $submitted))->toBe($post);
})->with([
    [[], 'old', 'none'], [['media_credit' => null], 'old', 'none'], [['media_credit' => 'same'], 'same', 'none'],
    [['media_credit' => 'new'], 'old', 'update'], [['media_credit' => ''], 'old', 'delete'],
    [['media_credit' => '0'], 'old', 'delete'], [['media_credit' => '1'], 1, 'update'],
]);

it('adds credits to core images using exactly the current caption rules', function (array $block, $saved, $metadata, string $input, string $expected): void {
    $lookup = $block['blockName'] === 'core/image' && isset($block['attrs']['id']);
    Functions\expect('get_post_meta')->times($lookup ? 1 : 0)->with(41, '_media_credit', true)->andReturn($saved);
    Functions\expect('wp_get_attachment_metadata')->times($lookup && $saved === null ? 1 : 0)->with(41)->andReturn($metadata);
    when('esc_html__')->alias(static fn ($text): string => 'T:'.$text);
    when('esc_html')->alias(static fn ($text): string => htmlspecialchars($text, ENT_QUOTES));

    expect(uamswp_core_image_add_credits($input, $block, new stdClass))->toBe($expected);
})->with([
    [['blockName' => 'core/paragraph', 'attrs' => ['id' => 41]], 'A', false, '<figure></figure>', '<figure></figure>'],
    [['blockName' => 'core/image', 'attrs' => []], 'A', false, '<figure></figure>', '<figure></figure>'],
    [['blockName' => 'core/image', 'attrs' => ['id' => 41]], '', false, '<figure></figure>', '<figure></figure>'],
    [['blockName' => 'core/image', 'attrs' => ['id' => 41]], 'A&B', false, '<figure><img></figure>', '<figure><img><figcaption><span class="photo-credit">(T:Image credit: A&amp;B)</span></figcaption></figure>'],
    [['blockName' => 'core/image', 'attrs' => ['id' => 41]], 'Credit', false, '<figure><figcaption>Caption</figcaption></figure>', '<figure><figcaption>Caption <span class="photo-credit">(T:Image credit: Credit)</span></figcaption></figure>'],
    [['blockName' => 'core/image', 'attrs' => ['id' => 41]], null, ['image_meta' => ['credit' => 'Fallback']], '<figure></figure>', '<figure><figcaption><span class="photo-credit">(T:Image credit: Fallback)</span></figcaption></figure>'],
    [['blockName' => 'core/image', 'attrs' => ['id' => 41]], null, false, '<figure></figure>', '<figure></figure>'],
    [['blockName' => 'core/image', 'attrs' => ['id' => 41]], 'Credit', false, '<div>no figure</div>', '<div>no figure</div>'],
]);

it('preserves loose block-name comparison for filter-supplied boolean names', function (): void {
    when('get_post_meta')->justReturn('Photographer');
    when('esc_html__')->alias(static fn ($value) => $value);
    when('esc_html')->alias(static fn ($value) => $value);

    $actual = uamswp_core_image_add_credits('<figure><img/></figure>', ['blockName' => true, 'attrs' => ['id' => 41]], null);
    expect($actual)->toContain('Photographer')->toContain('photo-credit');
});
