<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support;

use PHPUnit\Framework\Assert;

use function Brain\Monkey\Functions\when;

/** Fixed external values for the unchanged branding renderers. */
final class LibraryBrandingScenario
{
    public static function configure(array $input): void
    {
        when('uams_get_site_info')->justReturn($input['site']);
        when('get_stylesheet_directory_uri')->justReturn('https://theme.test');
        when('esc_html')->alias(static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES));
        when('esc_attr')->alias(static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES));
        when('esc_url')->alias(static fn ($value): string => 'URL:'.$value);
        when('format_phone')->alias(static fn ($format, $value): string => $format.':'.$value);
        when('get_field')->alias(static function ($key, $scope) use ($input) {
            Assert::assertSame('option', $scope);
            Assert::assertContains($key, ['uamswp_address', 'uamswp_primary_phone_text', 'uamswp_primary_phone_number', 'uamswp_secondary_number_type', 'uamswp_secondary_link_text', 'uamswp_secondary_link_url', 'uamswp_secondary_phone_text', 'uamswp_secondary_phone_number']);

            return $input['fields'][$key] ?? false;
        });
        if (isset($input['subsection_sequence'])) {
            $values = $input['subsection_sequence'];
            $index = 0;
            when('uamswp_nav_subsection')->alias(static function () use ($values, &$index) {
                return $values[min($index++, count($values) - 1)];
            });
        } else {
            when('uamswp_nav_subsection')->justReturn($input['subsection'] ?? false);
        }

        when('uams_site_title')->justReturn($input['title'] ?? 'Current Title');
        when('get_the_title')->alias(static fn ($id): string => 'PAGE:'.json_encode($id));
        when('get_the_permalink')->alias(static fn ($id): string => '/page/'.json_encode($id));
        when('is_page_template')->alias(static function ($name) use ($input): bool {
            Assert::assertSame('templates/marketing.php', $name);

            return $input['marketing'] ?? false;
        });
        $root = false;
        when('switch_to_blog')->alias(static function ($id) use (&$root): void {
            Assert::assertSame(1, $id);
            Assert::assertFalse($root);
            $root = true;
        });
        when('restore_current_blog')->alias(static function () use (&$root): void {
            Assert::assertTrue($root);
            $root = false;
        });
        when('get_bloginfo')->alias(static function ($key) use (&$root, $input): string {
            Assert::assertContains($key, ['name', 'description']);

            return $key === 'description' ? 'Description <&>' : ($root ? ($input['root_title'] ?? 'Root Title') : 'Local Name');
        });
        when('network_site_url')->alias(static function () use (&$root): string {
            Assert::assertFalse($root);

            return 'https://network.test/';
        });
        when('home_url')->alias(static function ($path) use (&$root): string {
            Assert::assertSame('/', $path);
            Assert::assertFalse($root);

            return 'https://site.test/';
        });
    }

    public static function renderCase(string $function, array $input): string
    {
        if (isset($input['warning'])) {
            return LibraryWarnings::capture($function, $input['warning'], static fn (): string => self::render($function));
        }

        return self::render($function);
    }

    public static function render(string $function): string
    {
        ob_start();
        try {
            $function();

            return ob_get_contents();
        } finally {
            ob_end_clean();
        }
    }
}
