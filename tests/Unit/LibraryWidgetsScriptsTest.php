<?php

declare(strict_types=1);

use Brain\Monkey\Functions;
use Uams2020\Tests\Support\LibraryLoader;
use Uams2020\Tests\Support\ModuleTemplateCapture;

use function Brain\Monkey\Actions\expectRemoved;
use function Brain\Monkey\Functions\when;

it('pins widget constructor registration options', function (string $class, array $expected): void {
    when('__')->alias(static fn ($text, $domain): string => $domain.':'.$text);
    $widget = new $class;
    expect($widget->constructorArguments)->toBe($expected);
})->with([
    [UAMSWP_Call_Out_Widget::class, ['uamswp_callout_widget', 'uamswp-uams-2020:UAMS Callout Widget', ['description' => 'uamswp-uams-2020:UAMS call out widget', 'classname' => 'uamswp-callout-widget']]],
    [UAMSWP_CTA_Widget::class, ['uamswp_cta_widget', 'uamswp-uams-2020:UAMS Call-to-Action Widget', ['description' => 'uamswp-uams-2020:UAMS call to action widget', 'classname' => 'uamswp-cta-widget']]],
]);

it('passes exact ACF widget values and wrappers to the shared block template', function (string $class, string $template, array $fields): void {
    when('__')->returnArg();
    $widget = new $class;
    ModuleTemplateCapture::$calls = [];
    $args = ['before_widget' => '<BEFORE>', 'after_widget' => '<AFTER>', 'widget_id' => 'chosen-9'];
    $instance = ['title' => 'Not displayed'];
    $variables = ['args' => $args, 'instance' => $instance, 'widget_id' => 'widget_chosen-9'];
    foreach ($fields as $variable => $field) {
        Functions\expect('get_field')->once()->with($field, 'widget_chosen-9')->andReturn('VALUE:'.$field);
        $variables[$variable] = 'VALUE:'.$field;
    }

    Functions\expect('get_stylesheet_directory')->once()->andReturn(__DIR__.'/../Fixtures/library-theme');
    ksort($variables);
    ob_start();
    $widget->widget($args, $instance);
    expect(ob_get_clean())->toBe('<BEFORE>[template:'.$template.']<AFTER>')->and(ModuleTemplateCapture::$calls)->toBe([['template' => $template, 'variables' => $variables]]);
})->with([
    [UAMSWP_Call_Out_Widget::class, 'call-out.php', ['heading' => 'call_out_heading', 'body' => 'call_out_body', 'use_image' => 'call_out_use_image', 'image' => 'call_out_image', 'background_color' => 'call_out_background_color']],
    [UAMSWP_CTA_Widget::class, 'cta.php', ['heading' => 'cta_bar_heading', 'body' => 'cta_bar_body', 'button_text' => 'cta_bar_button_text', 'button_url' => 'cta_bar_button_url', 'button_target' => 'cta_bar_button_target', 'button_desc' => 'cta_bar_button_description', 'layout' => 'cta_bar_layout', 'use_image' => 'cta_bar_use_image', 'image' => 'cta_bar_image', 'background_color' => 'cta_bar_background_color']],
]);

$widgetFormCases = [];
foreach ([UAMSWP_Call_Out_Widget::class, UAMSWP_CTA_Widget::class] as $class) {
    foreach ([[], ['title' => null], ['title' => 'A"<&>']] as $input) {
        $widgetFormCases[] = [$class, $input];
    }
}

it('pins widget field names, escaping and empty-title defaults', function (string $class, array $input): void {
    when('__')->returnArg();
    Functions\expect('_e')->once()->with('Title (Not Displayed)')->andReturnUsing(static function (): void {
        echo 'Translated Title';
    });
    Functions\expect('esc_attr')->once()->with($input['title'] ?? '')->andReturn('ESCAPED');
    $widget = new $class;
    ob_start();
    $widget->form($input);
    expect(ob_get_clean())->toBe("    <p>\n      <label for=\"FIELD-ID:title\">Translated Title</label>\n      <input class=\"widefat\" id=\"FIELD-ID:title\" name=\"FIELD-NAME:title\" type=\"text\" value=\"ESCAPED\">\n    </p>\n    ");
})->with($widgetFormCases);

$widgetUpdateCases = [];
foreach ([UAMSWP_Call_Out_Widget::class, UAMSWP_CTA_Widget::class] as $class) {
    foreach ([[[], ''], [['title' => null], ''], [['title' => '0'], ''], [['title' => '<b>Title</b> & text', 'extra' => 'ignored'], 'Title & text']] as [$input, $output]) {
        $widgetUpdateCases[] = [$class, $input, $output];
    }
}

it('strips tags from saved widget titles and discards other fields', function (string $class, array $input, string $output): void {
    when('__')->returnArg();
    expect((new $class)->update($input, ['title' => 'old', 'other' => 'old']))->toBe(['title' => $output]);
})->with($widgetUpdateCases);

it('registers exactly the two UAMS widget classes', function (): void {
    Functions\expect('register_widget')->once()->with('UAMSWP_Call_Out_Widget');
    Functions\expect('register_widget')->once()->with('UAMSWP_CTA_Widget');
    LibraryLoader::callback('widgets_init')();
});

it('pins the editor stylesheet and additional CORS origins', function (): void {
    Functions\expect('get_stylesheet_uri')->once()->andReturn('https://theme.test/style.css');
    Functions\expect('add_editor_style')->once()->with('https://theme.test/style.css');
    uamswp_custom_editor_css();
    expect(add_allowed_origins(['keep']))->toBe(['keep', 'https://www.uams.edu', 'https://public-api.wordpress.com', 'https://uamshealth.com', 'https://news.uams.edu']);
});

it('adds the origins an install lists for itself after the built-in ones', function (): void {
    Functions\expect('uamswp_framing_list')->once()->with('UAMSWP_EXTRA_ALLOWED_ORIGINS')->andReturn(['https://site.test', 'https://other.test:8443']);
    expect(add_allowed_origins(['keep']))->toBe(['keep', 'https://www.uams.edu', 'https://public-api.wordpress.com', 'https://uamshealth.com', 'https://news.uams.edu', 'https://site.test', 'https://other.test:8443']);
});

it('reads a per-install origin list from a constant and keeps only plain origins', function (): void {
    expect(uamswp_framing_list('UAMSWP_TEST_UNDEFINED_ORIGINS'))->toBe([]);

    define('UAMSWP_TEST_NOT_AN_ARRAY', 'https://site.test');
    expect(uamswp_framing_list('UAMSWP_TEST_NOT_AN_ARRAY'))->toBe([]);

    define('UAMSWP_TEST_ORIGINS', [
        'https://site.test', 'http://localhost:8080', 'https://a-b.example.org', 'https://site.test/path', 'https://site.test; script-src *',
        'ftp://site.test', 'site.test', 'https://', "https://site.test\r\nX-Evil: 1", 7, null, ['https://nested.test'],
    ]);
    expect(uamswp_framing_list('UAMSWP_TEST_ORIGINS'))->toBe(['https://site.test', 'http://localhost:8080', 'https://a-b.example.org']);
});

it('sends the framing headers only when the install lists frame ancestors', function (array $ancestors, array $sent): void {
    Functions\expect('uamswp_framing_list')->once()->with('UAMSWP_FRAME_ANCESTORS')->andReturn($ancestors);
    $headers = [];
    when('header')->alias(static function (string $header) use (&$headers): void {
        $headers[] = $header;
    });
    uamswp_send_frame_headers();
    expect($headers)->toBe($sent);
})->with([
    'unset' => [[], []],
    'one origin' => [['https://www.uams.edu'], ['X-Frame-Options: SAMEORIGIN', "Content-Security-Policy: frame-ancestors 'self' https://www.uams.edu;"]],
    'three origins' => [['https://www.uams.edu', 'https://sites.uams.edu', 'https://uamshealth.dev'], ['X-Frame-Options: SAMEORIGIN', "Content-Security-Policy: frame-ancestors 'self' https://www.uams.edu https://sites.uams.edu https://uamshealth.dev;"]],
]);

it('loads canvas fonts only in the editor', function (bool $admin): void {
    Functions\expect('is_admin')->once()->andReturn($admin);
    Functions\expect('wp_enqueue_style')->times($admin ? 1 : 0)->with('theme-fonts', 'https://fonts.googleapis.com/css2?family=Fira+Sans&display=swap');
    uamswp_block_canvas_assets();
    expect(uamswp_theme_fonts_url())->toBe('https://fonts.googleapis.com/css2?family=Fira+Sans&display=swap');
})->with([true, false]);

it('removes the block directory on admin initialization and pins editor theme supports', function (): void {
    expectRemoved('enqueue_block_editor_assets')->once()->with('wp_enqueue_editor_block_directory_assets');
    LibraryLoader::callback('admin_init')();
    $calls = [];
    when('add_theme_support')->alias(static function (...$args) use (&$calls): void {
        $calls[] = $args;
    });
    uamswp_gutenberg_disable_all_colors_fonts();
    expect($calls)->toBe([['editor-color-palette', []], ['disable-custom-colors'], ['editor-font-sizes', []], ['disable-custom-font-sizes']]);
});

it('allows CTA widget subclasses to supply null or false-like field names', function ($fieldName, string $expectedName): void {
    when('__')->returnArg();
    Functions\expect('_e')->once()->with('Title (Not Displayed)')->andReturnUsing(static function (): void {
        echo 'Translated Title';
    });
    Functions\expect('esc_attr')->once()->with('')->andReturn('ESCAPED');
    $widget = new class($fieldName) extends UAMSWP_CTA_Widget
    {
        public function __construct(private mixed $fieldName)
        {
            parent::__construct();
        }

        public function get_field_name($field): mixed
        {
            expect($field)->toBe('title');

            return $this->fieldName;
        }
    };
    ob_start();
    $widget->form([]);
    expect(ob_get_clean())->toBe("    <p>\n      <label for=\"FIELD-ID:title\">Translated Title</label>\n      <input class=\"widefat\" id=\"FIELD-ID:title\" name=\"".$expectedName."\" type=\"text\" value=\"ESCAPED\">\n    </p>\n    ");
})->with([[null, ''], ['0', '0'], ['', '']]);
