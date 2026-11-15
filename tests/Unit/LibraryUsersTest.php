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

it('grants nothing when the editor role does not exist', function (): void {
    Functions\expect('get_role')->once()->with('editor')->andReturn(null);
    uamswp_editor_users();
});

it('stores the login timestamp against user zero when the user is not an object or has no usable ID', function ($user, int $expected): void {
    Functions\expect('time')->once()->withNoArgs()->andReturn(1700000123);
    Functions\expect('update_user_meta')->once()->withArgs(static fn ($id, $key, $value): bool => $id === $expected && $key === 'last_login' && $value === 1700000123);
    uamswp_user_last_login('ignored login', $user);
})->with([[null, 0], [false, 0], [['ID' => 37], 0], [(object) [], 0], [(object) ['ID' => ['37']], 0], [(object) ['ID' => '37'], 37], [(object) ['ID' => 37], 37]]);

it('shows no last login for an author without a usable timestamp', function ($stored): void {
    Functions\expect('is_user_logged_in')->once()->andReturn(true);
    Functions\expect('get_the_author_meta')->once()->with('last_login')->andReturn($stored);
    Functions\expect('human_time_diff')->never();
    expect(uamswp_lastlogin())->toBe('');
})->with([[''], [false], [null], [['1700000123']], ['never'], ['0']]);

it('passes a stored string timestamp to the time difference as an integer', function (): void {
    Functions\expect('is_user_logged_in')->once()->andReturn(true);
    Functions\expect('get_the_author_meta')->once()->with('last_login')->andReturn('1700000123');
    Functions\expect('human_time_diff')->once()->withArgs(static fn ($from): bool => $from === 1700000123)->andReturn('one hour');
    expect(uamswp_lastlogin())->toBe('one hour');
});

it('adds the login column to a column list that is not an array', function ($columns): void {
    expect(uamswp_modify_user_table($columns))->toBe(['last_login' => 'Last Login']);
})->with([[null], [false], ['name']]);

it('reads the login-column user ID and timestamp as integers', function ($userId, $stored, string $expected): void {
    Functions\expect('__')->zeroOrMoreTimes()->with('Never', 'uamswp')->andReturn('Never translated');
    Functions\expect('get_the_author_meta')->once()->withArgs(static fn ($key, $id): bool => $key === 'last_login' && $id === 37)->andReturn($stored);
    Functions\expect('human_time_diff')->zeroOrMoreTimes()->withArgs(static fn ($from): bool => $from === 1700000123)->andReturn('one hour');
    expect(uamswp_modify_user_table_row('ORIGINAL', 'last_login', $userId))->toBe($expected);
})->with([['37', '1700000123', 'one hour'], [37, 1700000123, 'one hour'], [37, ['x'], 'Never translated'], [37, 'never', 'Never translated'], [37, null, 'Never translated']]);

it('reads a login-column user ID that is not text as the current author', function ($userId): void {
    Functions\expect('__')->once()->with('Never', 'uamswp')->andReturn('Never translated');
    Functions\expect('get_the_author_meta')->once()->withArgs(static fn ($key, $id): bool => $key === 'last_login' && $id === 0)->andReturn('');
    expect(uamswp_modify_user_table_row('ORIGINAL', 'last_login', $userId))->toBe('Never translated');
})->with([[null], [false], [['37']]]);
