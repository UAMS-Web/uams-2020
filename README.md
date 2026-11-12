# UAMS 2020 Theme

## Installation Instructions

1. Upload the Bootstrap for Genesis theme folder via FTP to your wp-content/themes/ directory. (The Genesis parent theme needs to be in the wp-content/themes/ directory as well.)
2. Go to your WordPress dashboard and select Appearance.
3. Activate the Bootstrap for Genesis theme.
4. Inside your WordPress dashboard, go to Genesis > Theme Settings and configure them to your liking.

## Building from Source

1. Install [Git](https://git-scm.com/).
2. Clone the repository to your local machine.
3. Install [Node](https://nodejs.org/en/).
4. Install [Gulp](https://gulpjs.com/) globally.
5. Install [Yarn](https://yarnpkg.com/en/docs/install)
6. Run `yarn install` to install dependencies through terminal/CLI program.
7. Replace site url in line `45` of `gulpfile.js` to your local development URL(e.g. http://bootstrap.test).
8. Run `gulp` through your favorite CLI program.

## Features

1. Bootstrap 4
2. Bootstrap components
	* Comment Form
	* Search Form
	* Jumbotron
	* Navbar
3. Sass
4. Gulp
5. Footer Widgets(modified to add bootstrap column classes based on the number of widget areas)
6. Additional Widget Areas
	* Home Featured(jumbotron)
7. TGM Plugin Activation Support
8. Multi-level dropdown menus using [SmartMenus](http://www.smartmenus.org/) Bootstrap Addon

## Credits

Developed and maintained by UAMS Web Services.

Developers:

- Rotsen Mark Acob
- Josh Daugherty
- Todd McKee
- Brent Passmore

Based on the [Bootstrap for Genesis](https://github.com/webdevsuperfast/bootstrap-for-genesis) theme by Rotsen Mark Acob

## Tests

```bash
composer install
composer test:unit
```

The supported PHP floor is 8.2. Development and tests run on PHP 8.4 (`config.platform.php` is `8.4.27`). The unit suite runs on Pest 5 with Brain Monkey. The integration suite runs on PHPUnit 9.6 from its own Composer setup in `tests/Integration/`: run `composer setup:integration`, create the `uams_2020_tests` database, then `composer test:integration`.

## Mutation testing

With Xdebug coverage mode or PCOV enabled, run `composer mutate` (it runs `php tests/mutate.php`) for every covered file. Pass `--path=<files>` to narrow a run. The populations the coverage slices closed against, and the command for each:

| Population | Command |
| --- | --- |
| Root templates, `functions.php` and `search.php` | `composer mutate -- --path=functions.php,home.php,page_archive.php,page_blog.php,search.php,templates` |
| `lib/` | `composer mutate -- --path=lib` |
| `acf_fields/` | `composer mutate -- --path=acf_fields` |
| `blocks/` | `composer mutate -- --path=blocks` |

The default run covers all four. The two vendored libraries that `phpunit.xml` excludes from coverage are excluded from it too. The launcher uses every unit test file and rejects an empty test list. Set the optional `UAMS_MUTATION_REPORT` environment variable to write per-mutation JSON results and replay paths. It disables cached results and requires every generated mutant to be caught; inspect any surviving or uncovered case before claiming full coverage.

The launcher copies the PHPUnit settings to a temporary XML file with explicit unit-test and source-file entries. This avoids a directory-walking conflict between the mutation loader and Patchwork while allowing the parallel runner to read one configuration file. It selects parallel execution automatically. Do not add `--processes`: the locked mutation tool forwards that option to children without `--parallel`, which can make failed starts look like caught mutations. The launcher rejects process-count, parallel, and configuration overrides. A temporary root-only patch corrects scalar function-default test selection; its scope, guard, and retirement plan are in [`patches/README.md`](patches/README.md). The installed revisions checked for these workarounds are `pestphp/pest-plugin-mutate` v5.0.2 at `d0c87110416b699413cc073b8dcc92e440d81931` and Patchwork 2.2.4 at `d5d5c6032b7109477163b68e25ff531f7654ebf9`, as pinned in `composer.lock`.

## Continuous integration

Pull requests are gated by the GitHub Actions workflow in [`.github/workflows/ci.yml`](.github/workflows/ci.yml). It runs Pint, Rector (dry-run), PHPStan, the mutation check, and the Unit suite on every push and pull request. A red check fails the workflow. The mutation check runs `composer mutate` with PCOV on Ubuntu over the covered files a pull request or push changes. A manual run mutates every covered file instead, in one `mutate-full` job for each of the root templates with `search.php`, `functions.php`, `lib`, `acf_fields` and `blocks`, because one job over all of them exceeds the 180-minute limit. It fails on any untested, uncovered, pending or timed-out mutant, and on a run that creates none; a mutant that no test can catch carries `@pest-mutate-ignore` with its reason.

Without these projects, this theme wouldn't be where it is today.

* [Genesis Framework](http://my.studiopress.com/themes/genesis/)
* [Bootstrap](http://getbootstrap.com)
* [Sass](http://sass-lang.com/)
* [Gulp](http://gulpjs.com/)
* [TGM Plugin Activation](http://tgmpluginactivation.com/)
* [WP Bootstrap Navwalker](https://github.com/twittem/wp-bootstrap-navwalker)
* [Bootstrap Genesis](https://github.com/salcode/bootstrap-genesis)
* [Bones for Genesis 2.0 with Bootstrap integration](https://github.com/jer0dh/bones-for-genesis-2-0-bootstrap)
* [SmartMenus Bootstrap Addon](http://www.smartmenus.org/)

## Tests

```bash
composer install
composer test:unit
```

The supported PHP floor is 8.2, and the `lint-floor` job in CI parses every shipped PHP file on it.

The rate limiter keeps its `array_filter()` call on one line. Patchwork removes whitespace after the opening parenthesis when rewriting that internal call. A newline there shifts later coverage hits away from the original source lines and makes mutation test selection unreliable. This layout keeps the executed and original line maps aligned without a vendor patch.

Isolated PHPUnit tests receive the Composer loader and, during mutation testing, the mutation stream wrapper before Patchwork loads. This keeps constant-sensitive tests on the mutated source rather than silently testing the original file.
