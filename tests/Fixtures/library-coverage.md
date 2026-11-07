# Remaining-library characterization

The fixtures pin the library population from commit `f09d12a455cbfc467b6a0260eaed9be7509f7fea` on macOS, PHP 8.4.27. Application files were unchanged when the fixtures were recorded. Mutation results are recorded separately after review and the final gating run.

## Population

The population is every first-party PHP file below `lib/` other than the four page-builder files covered separately. The two vendored libraries are excluded from both Unit and Integration coverage: `lib/modules/class-tgm-plugin-activation.php` and `lib/modules/class-wp-bootstrap-navwalker.php`.

- `lib/a11y.php`
- `lib/bootstrap.php`
- `lib/breadcrumb.php`
- `lib/comment.php`
- `lib/customizer.php`
- `lib/extras.php`
- `lib/favicon.php`
- `lib/footer-widgets.php`
- `lib/footer.php`
- `lib/geo.php`
- `lib/header.php`
- `lib/media-credit.php`
- `lib/misc.php`
- `lib/modules/class-wp-bootstrap-pagewalker.php`
- `lib/nav.php`
- `lib/page.php`
- `lib/pagination.php`
- `lib/quicklinks.php`
- `lib/scripts.php`
- `lib/search-tray.php`
- `lib/search.php`
- `lib/site-container-outer.php`
- `lib/tgm.php`
- `lib/users.php`
- `lib/widget-areas.php`
- `lib/widgets.php`

## Fixture boundaries

- `library-hook-registrations.json` records load-time additions, removals, priorities, accepted arguments and other setup calls. `LibraryLoader` checks it before clearing Brain Monkey state. Header and title-option runtime registrations have their own exact argument checks.
- Branding, page-image, quick-link tray, search, favicon and plugin-registration fixtures are fixed output from the unchanged source with explicit external values. Tests read them; they never update expectations from source at runtime. Complete markup is compared, including whitespace and existing irregular markup.
- Footer year markers are replaced with the current year in the expectation. No other output is normalized. Header site-switch stubs check balanced calls; the separate WordPress network test checks real site IDs and the full switch stack.
- Region fixtures list all configured cities individually. The loader also checks the complete five region lists at initialization, when mutation coverage attributes their assignments. Additional cases exercise wrong states, unknown cities and region order. The include helper intentionally returns null after a recognized region misses a city, and false on an unknown region.
- Template fixtures record include inputs instead of rendering blocks. Block rendering has a separate coverage issue. Widget constructor and walker parent doubles only expose unit boundaries; WordPress integration checks actual widget IDs, field names and tree output.
- Repeated subsection reads may change through real WordPress metadata filters. Sequenced header cases cover those paths; they are not treated as dead code just because an earlier lookup returned false. Widget getter boundaries keep WordPress' untyped signatures so legitimate widget subclasses can return null or a false-like field name.
- The existing block-registry double is shared with root-helper tests, with its data restored after use. No second competing registry implementation is loaded.
- The WordPress network command uses an empty ACF presence marker so the theme reads site settings from actual WordPress options. This marker implements no ACF API. No claim is made about an installed ACF implementation.

## Existing behavior pinned without fixes

- A one-character current page label emits `Uninitialized string offset 1` before returning active-page markup.
- A nested page menu with no preceding link ID emits `Undefined variable $labledby` and leaves the labeling attribute absent.
- A quick-link callback without a primary menu location emits `Undefined array key "primary"` while building its missing-menu error.
- The image extractor selects the last matching image on one line, while a newline separates matches. The test pins this greedy regex behavior.
- A false quick-link item response emits four property-read warnings and an empty link after the existing array cast.
- Two custom addresses with a missing title emit `Undefined variable $address_sr`.
- Array blog names supplied by mixed option filters emit three `Array to string conversion` warnings before the current title is returned.
- The admin-menu callback creates a user object but never sets its local role variable, so its editor-only menu removal body does not run.

Warning tests accept the exact specified warning list from the compiled function or method range. Other errors are thrown. Reflection boundaries allow the mutation printer to move lines. A temporary control pretty-printed all 26 files without changing behavior, ran the full Unit suite, restored every original byte, and passed. Final mutation conclusions still require a separate complete run.

## Test isolation controls

Independent review found two test-only defects. The footer sidebar double now checks the expected call bound and exact next sidebar ID before writing output. Removing the production counter increment causes eight bounded assertion failures, instead of waiting for a timeout. The WordPress widget test restores the factory and all three core registration arrays. Disabling that restoration fails the explicit global-state assertion. Both temporary controls were restored before validation.

## Global configuration and mutation selection

An initial mutation run was stopped without a completed result after it selected only first-load tests for changes to a global city list. The selected tests did not include later city consumers. Replaying a mutation that removed Sheridan passed all 154 selected tests, while the actual city tests failed on the missing membership. A fixed-data assertion now compares all five complete lists at initialization. The identical selected-test replay then fails on the changed central list. The functional city cases remain in place. No mutation-tool patch or dependency change was needed.

## WordPress commands

Set `WP_TESTS_ABSPATH` to a clean WordPress 7.1.3 core tree and use the isolated `uams_2020_tests` database configured by `tests/Integration/wp-tests-config.php`.

```sh
php tests/Integration/vendor/bin/phpunit --configuration tests/Integration/phpunit.xml --do-not-cache-result
WP_MULTISITE=1 UAMS_LIBRARY_SITE_CONTEXT=1 php tests/Integration/vendor/bin/phpunit --configuration tests/Integration/phpunit.xml --filter LibraryMultisiteBehaviorTest --do-not-cache-result
```

The normal command deliberately skips the two network-only cases. The second command runs both cases on a real network. The site marker is enabled only in the second process.

The first-party page walker keeps its existing `lib/modules` Pint exclusion. Its
coverage changes add only narrow mutation comments. Applying the formatter's
strict-types declaration would reject numeric and Stringable titles that its
existing weak calls accept; the page-walker tests pin both values.

Genesis helper return values are mocked in the Unit suite. The wrapper-order case
pins this function's conversion order for a Stringable boundary value. It is not
an integration claim about an installed Genesis engine.
