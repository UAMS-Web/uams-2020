<?php

declare(strict_types=1);

use Brain\Monkey\Functions;

it('grants only the current editor capability set', function (): void {
    $role = Mockery::mock();
    Functions\expect('get_role')->once()->with('editor')->andReturn($role);
    foreach (['edit_theme_options', 'gravityforms_edit_forms', 'gravityforms_delete_forms', 'gravityforms_create_form', 'gravityforms_view_entries', 'gravityforms_edit_entries', 'gravityforms_delete_entries', 'gravityforms_view_settings', 'gravityforms_edit_settings', 'gravityforms_export_entries', 'gravityforms_view_entry_notes', 'gravityforms_edit_entry_notes'] as $capability) {
        $role->shouldReceive('add_cap')->once()->with($capability);
    }

    uamswp_editor_users();
});

it('hides the Themes and Widgets entries from editors by slug', function (bool $admin, $roles, bool $hidden): void {
    Functions\expect('current_user_can')->once()->with('manage_options')->andReturn($admin);
    $user = $roles === null ? new stdClass : new WP_User;
    if ($user instanceof WP_User) {
        $user->roles = $roles;
    }

    Functions\expect('wp_get_current_user')->times($admin ? 0 : 1)->andReturn($user);
    if ($hidden) {
        Functions\expect('remove_submenu_page')->once()->with('themes.php', 'themes.php');
        Functions\expect('remove_submenu_page')->once()->with('themes.php', 'widgets.php');
    } else {
        Functions\expect('remove_submenu_page')->never();
    }

    $oldSubmenu = $GLOBALS['submenu'] ?? null;
    $GLOBALS['submenu'] = ['themes.php' => [6 => 'design', 15 => 'header']];
    try {
        custom_admin_menu();
        expect($GLOBALS['submenu'])->toBe(['themes.php' => [6 => 'design', 15 => 'header']]);
    } finally {
        $GLOBALS['submenu'] = $oldSubmenu;
    }
})->with([
    'editor' => [false, ['editor'], true],
    'editor with another role' => [false, ['author', 'editor'], true],
    'administrator' => [true, ['editor'], false],
    'author' => [false, ['author'], false],
    'role compared case-sensitively' => [false, ['Editor'], false],
    'no user object' => [false, null, false],
]);

it('guards editor-only theme screens while allowing administrators and other screens', function (bool $admin, $roles, string $screen, bool $denied): void {
    $oldPage = $GLOBALS['pagenow'] ?? null;
    $GLOBALS['pagenow'] = $screen;
    Functions\expect('current_user_can')->once()->with('manage_options')->andReturn($admin);
    $user = $roles === null ? new stdClass : new WP_User;
    if ($user instanceof WP_User) {
        $user->roles = $roles;
    }

    Functions\expect('wp_get_current_user')->times($admin ? 0 : 1)->andReturn($user);
    Functions\expect('__')->times($denied ? 1 : 0)->with('Sorry, you are not allowed to access this page.')->andReturn('Denied');
    Functions\expect('wp_die')->times($denied ? 1 : 0)->with('Denied', '', ['response' => 403]);
    try {
        uamswp_restrict_editor_theme_screens();
    } finally {
        $GLOBALS['pagenow'] = $oldPage;
    }
})->with([
    [true, ['editor'], 'themes.php', false], [false, null, 'themes.php', false],
    [false, ['author'], 'themes.php', false], [false, ['Editor'], 'themes.php', false],
    [false, ['editor'], 'themes.php', true], [false, ['editor'], 'widgets.php', true],
    [false, ['author', 'editor'], 'themes.php', true], [false, (object) ['primary' => 'editor'], 'themes.php', true], [false, ['editor'], 'nav-menus.php', false],
    [false, ['editor'], 'customize.php', false], [false, ['editor'], 'THEMES.php', false],
]);

it('removes only the widgets Customizer component for restricted editors', function (bool $admin, $roles, array $expected): void {
    Functions\expect('current_user_can')->once()->with('manage_options')->andReturn($admin);
    $user = $roles === null ? new stdClass : new WP_User;
    if ($user instanceof WP_User) {
        $user->roles = $roles;
    }

    Functions\expect('wp_get_current_user')->times($admin ? 0 : 1)->andReturn($user);

    expect(uamswp_close_customizer_widgets_for_editors(['widgets', 'nav_menus', 'selective_refresh']))->toBe($expected);
})->with([
    'editor' => [false, ['editor'], ['nav_menus', 'selective_refresh']],
    'editor with another role' => [false, ['author', 'editor'], ['nav_menus', 'selective_refresh']],
    'administrator' => [true, ['editor'], ['widgets', 'nav_menus', 'selective_refresh']],
    'author' => [false, ['author'], ['widgets', 'nav_menus', 'selective_refresh']],
    'no user object' => [false, null, ['widgets', 'nav_menus', 'selective_refresh']],
]);

it('stores the login timestamp under the authenticated user ID', function (): void {
    Functions\expect('time')->once()->withNoArgs()->andReturn(1700000123);
    Functions\expect('update_user_meta')->once()->with(37, 'last_login', 1700000123);
    uamswp_user_last_login('ignored login', (object) ['ID' => 37]);
});

it('shows author login activity only to authenticated visitors', function (bool $loggedIn): void {
    Functions\expect('is_user_logged_in')->once()->andReturn($loggedIn);
    Functions\expect('get_the_author_meta')->times($loggedIn ? 1 : 0)->with('last_login')->andReturn(1700000123);
    Functions\expect('human_time_diff')->times($loggedIn ? 1 : 0)->with(1700000123)->andReturn('one hour');
    expect(uamswp_lastlogin())->toBe($loggedIn ? 'one hour' : '');
})->with([true, false]);

it('adds the login column while preserving other columns', function (): void {
    expect(uamswp_modify_user_table(['name' => 'Name', 'last_login' => 'old']))->toBe(['name' => 'Name', 'last_login' => 'Last Login']);
});

it('renders login-column timestamps and preserves unrelated cells', function (string $column, $timestamp, string $expected): void {
    $loginColumn = $column === 'last_login';
    Functions\expect('__')->times($loginColumn ? 1 : 0)->with('Never', 'uamswp')->andReturn('Never translated');
    Functions\expect('get_the_author_meta')->times($loginColumn ? 1 : 0)->with('last_login', 37)->andReturn($timestamp);
    Functions\expect('human_time_diff')->times($loginColumn && $timestamp ? 1 : 0)->with($timestamp)->andReturn('one hour');
    expect(uamswp_modify_user_table_row('ORIGINAL', $column, 37))->toBe($expected);
})->with([['other', 123, 'ORIGINAL'], ['last_login', 123, 'one hour'], ['last_login', '', 'Never translated'], ['last_login', '0', 'Never translated']]);
