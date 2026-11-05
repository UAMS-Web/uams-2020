# Block template characterization

`block-templates.json` contains fixed inputs and expected complete markup, ordered field and region calls, and query arguments for the 18 PHP render templates in `blocks/`. The capture was made before editing any application file. No fixture is regenerated during a test. The input matrix includes field lookups and caller-supplied values, IDs and anchors, both image-plugin states, empty and repeated rows, editor previews, region inclusion and exclusion, counter dates and thresholds, gallery columns and crops, quote layouts, and news formats.

The fixture records current behavior rather than desired markup. For example, the gallery and stacked templates evaluate their more-link target expression without echoing it; their existing output has no target attribute. The legacy second link-list and stacked templates can retain the preceding row's link values when the next row has no URL. These outputs are pinned, not repaired here.

## Boundaries

`BlockTemplateScenario` includes each template in a fresh local scope with only the listed caller variables. WordPress and plugin functions are mocked. Distinct escaping markers distinguish HTML, attribute, URL, post-content and class sanitization calls; these markers are instruments, not implementations of WordPress escaping. Image helpers expose their meaningful arguments, including crop dimensions and fallback sizes. The attachment URL helper accepts its real single argument and ignores the legacy extra size argument. QueryDouble models the loop boundary, not database selection; its null `ID` matches WordPress 7.1.3's `WP_Query::__get` for a non-compatibility property. Real thumbnail lookup therefore falls back to the current post.

Counter time is fixed to 2024-03-15 12:00:00 UTC. Date formatting and relative-date parsing are replaced only during each case and use that fixed UTC base. Cases exercise day, week, month, year and custom starts, inherited and overridden starts, negative and zero elapsed time, the one-second boundary, fractional rates and integer display thresholds. JavaScript is compared as emitted text, not executed in a browser.

The separate WordPress integration tests use real category storage, query selection, published/draft status, current-post thumbnail lookup, escaping, filters and shortcode parsing. Only ACF field retrieval and the news shortcode's plugin callback are test doubles. They make no claim about an installed ACF, image-resizer, news or browser engine. The iframe test checks actual host and sandbox filters, rejection and preview output with real WordPress escaping.

Strict identity compares the complete result, including types, array order and whitespace. The failure message is deliberately bounded so a mutation cannot trigger a multi-megabyte diff. Missing field values fail rather than silently returning a default. No blanket error suppression is used.

A generous 2,000-call escaping limit makes non-terminating template loops fail inside the test rather than consume unbounded memory. Normal fixtures stay far below this limit. QueryDouble separately rejects loops that read more than 100 times without advancing. These guards run after the template starts; they are not bootstrap or command failures.
