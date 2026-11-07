# ACF field-definition characterization

The fixed fixture was recorded from unchanged application files at
`7d4840b4cf7ab6dfabee419f75d68cbc43019958` on macOS with PHP 8.4.27.

The population is every PHP file in `acf_fields/`: 17 files and 6,766 lines.
Both coverage configurations include this directory. No application file is
changed by this coverage work.

## Fixture boundaries

- `acf-field-schemas.json` contains 53 fixed cases. Tests read those values; they do not regenerate expected data from source.
- Each of the 16 individual field definitions is recorded for `_b`, `_m`, and `_o`, the block, module, and page-option suffix values used by the theme. This tests the include contract beyond the current callers; it does not claim every file is currently used in all three contexts.
- News definitions are also recorded with the news plugin absent for all three suffixes. The fallback is the existing activation message, not an empty field list.
- Module definitions receive either 14 distinct child-field markers or empty arrays. Distinct markers expose a swapped or omitted include variable.
- Two additional cases compose the actual child definitions and compare the result with the module fields in the existing complete registration fixtures, with the news plugin present and absent.
- JSON capture preserves numeric strings, scalar types, array order, and floating-point zero fractions. The assertions use strict identity over complete arrays with a bounded failure message.

The production files return data. Their only function call is the news file's
`class_exists` check. Unit tests mock that plugin-availability boundary. They do
not claim validation by an installed ACF or news plugin. Existing registration
and WordPress tests remain part of the complete suite.

## Controls

Four temporary mutations were replayed through the real source loader: a removed
CTA field, a removed hero-key suffix, a reversed news-plugin condition, and a
removed module field. Each fails on a changed returned schema. The original
55 cases pass. The application files remain byte-for-byte unchanged.

Complete mutation results and equivalent explanations, if any, are recorded on
the coverage issue after the reviewed full run.
