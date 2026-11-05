<?php

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Filters\expectApplied;
use function Brain\Monkey\Functions\when;
use function Patchwork\relay;

beforeEach(function (): void {
    when('function_exists')->alias(static fn ($function) => $function === 'bis_get_attachment_image' ? true : relay());
    when('time')->justReturn(1000);
});

it('refuses resizing without the image plugin', function (): void {
    when('function_exists')->alias(static fn ($function) => $function === 'bis_get_attachment_image' ? false : relay());
    Functions\expect('wp_get_attachment_image_src')->never();
    expect(image_sizer(42, 100, 50))->toBeNull();
});

it('refuses empty attachments before reading image dimensions', function ($id): void {
    Functions\expect('wp_get_attachment_image_src')->never();
    expect(image_sizer($id, 100, 50))->toBeNull();
})->with([[null], [0], ['']]);

it('refuses an attachment without image metadata', function (): void {
    Functions\expect('wp_get_attachment_image_src')->once()->with(42)->andReturn(false);
    expect(image_sizer(42, 100, 50))->toBeNull();
});

it('returns cached values including an empty cached URL', function ($cached): void {
    when('wp_get_attachment_image_src')->justReturn(['original.jpg', 400, 200]);
    Functions\expect('get_transient')->once()->with('image_sizer_'.md5('4210050leftright'))->andReturn($cached);
    Functions\expect('get_option')->never();
    expect(image_sizer(42, 100, 50, 'left', 'right'))->toBe($cached);
})->with([['cached.jpg'], [''], [null]]);

it('queues the current crop dimensions and schedules the first batch', function (array $source, int|string $width, int|string $height, array $dimensions): void {
    when('wp_get_attachment_image_src')->justReturn(['original.jpg', ...$source]);
    when('get_transient')->justReturn(false);
    Functions\expect('get_option')->once()->with('image_sizer_queue', [])->andReturn([]);
    Functions\expect('update_option')->once()->with('image_sizer_queue', [[
        'id' => 42,
        'params' => ['width' => $dimensions[0], 'height' => $dimensions[1], 'hcrop' => 'left', 'vcrop' => 'top'],
        'transient_key' => 'image_sizer_'.md5('42'.$width.$height.'lefttop'),
    ]], false);
    Functions\expect('wp_next_scheduled')->once()->with('process_image_sizer_queue')->andReturn(false);
    Functions\expect('wp_schedule_event')->once()->with(1000, 'ten_seconds', 'process_image_sizer_queue');
    Functions\expect('image_sizer_get_fallback')->once()->with(42, 'medium')->andReturn('fallback.jpg');
    expect(image_sizer(42, $width, $height, 'left', 'top', 'medium'))->toBe('fallback.jpg');
})->with([
    [[400, 200], 100, 50, [100, 50]],
    [[100, 50], 100, 50, [100, 50]],
    [[400, 200], 800, 800, [200, 200]],
    [[200, 400], 800, 800, [200, 200]],
    [[400, 200], 800, 100, [400, 50]],
    [[200, 400], 100, 800, [50, 400]],
    [[400, 200], 100, -1, [100, 50.0]],
    [[400, 200], 100, '-1', [100, 50.0]],
    [[400, 200], -1, 50, [100.0, 50]],
    [[400, 200], '-1', 50, [100.0, 50]],
]);

it('does not upscale a smaller image with the preferred ratio', function (): void {
    when('wp_get_attachment_image_src')->justReturn(['original.jpg', 400, 200]);
    when('get_transient')->justReturn(false);

    Functions\expect('get_option')->never();
    Functions\expect('image_sizer_get_fallback')->once()->with(42, 'full')->andReturn('original.jpg');
    expect(image_sizer(42, 800, 400))->toBe('original.jpg');
});

it('does not append an existing queued crop', function (): void {
    when('wp_get_attachment_image_src')->justReturn(['original.jpg', 400, 200]);
    when('get_transient')->justReturn(false);
    when('get_option')->justReturn([
        ['id' => 7, 'params' => ['width' => 100, 'height' => 50, 'hcrop' => 'center', 'vcrop' => 'center']],
        ['id' => 42, 'params' => ['width' => 100, 'height' => 50, 'hcrop' => 'center', 'vcrop' => 'center']],
    ]);
    Functions\expect('update_option')->never();
    Functions\expect('wp_schedule_event')->never();
    Functions\expect('image_sizer_get_fallback')->once()->with(42, 'full')->andReturn('fallback.jpg');
    expect(image_sizer(42, 100, 50))->toBe('fallback.jpg');
});

it('preserves earlier queued crops without scheduling a duplicate cron event', function (): void {
    $previous = ['id' => 7, 'params' => ['width' => 100, 'height' => 50, 'hcrop' => 'center', 'vcrop' => 'center']];
    when('wp_get_attachment_image_src')->justReturn(['original.jpg', 400, 200]);
    when('get_transient')->justReturn(false);
    when('get_option')->justReturn([$previous]);
    Functions\expect('update_option')->once()->with('image_sizer_queue', [$previous, [
        'id' => 42, 'params' => ['width' => 100, 'height' => 50, 'hcrop' => 'center', 'vcrop' => 'center'],
        'transient_key' => 'image_sizer_'.md5('4210050centercenter'),
    ]], false);
    when('wp_next_scheduled')->justReturn(1000);
    Functions\expect('wp_schedule_event')->never();
    when('image_sizer_get_fallback')->justReturn('fallback.jpg');
    expect(image_sizer(42, 100, 50))->toBe('fallback.jpg');
});

it('returns a requested fallback URL before using the filtered default', function ($requested, $expectedSize): void {
    expectApplied('image_sizer_default_fallback')->once()->with('full')->andReturn('medium');
    Functions\expect('wp_get_attachment_image_src')->once()->with(42, $expectedSize)->andReturn(['fallback.jpg', 100, 50]);
    expect(image_sizer_get_fallback(42, $requested))->toBe('fallback.jpg');
})->with([['thumbnail', 'thumbnail'], ['', 'medium'], [null, 'medium']]);

it('falls back to the default image when a requested size is absent', function (): void {
    Functions\expect('wp_get_attachment_image_src')->once()->with(42, 'missing')->andReturn(false);
    Functions\expect('wp_get_attachment_image_src')->once()->with(42, 'full')->andReturn(['full.jpg', 400, 200]);
    expect(image_sizer_get_fallback(42, 'missing'))->toBe('full.jpg');
});

it('returns an empty URL when the default fallback has an empty URL', function (): void {
    Functions\expect('wp_get_attachment_image_src')->once()->with(42, 'missing')->andReturn(false);
    Functions\expect('wp_get_attachment_image_src')->once()->with(42, 'full')->andReturn(['', 0, 0]);
    expect(image_sizer_get_fallback(42, 'missing'))->toBe('');
});
