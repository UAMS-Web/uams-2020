# Blocks

<!-- cspell:ignore lightbox livewhale uams uamswp -->

Written against v1.10.2.

The theme registers its blocks through Advanced Custom Fields PRO, so they exist only while that plugin is active. Every block has the `uams` keyword: type `uams` in the block inserter to list them all. Each block accepts an HTML anchor.

## The blocks

| Block | Name | Inserter category | What it is for |
| --- | --- | --- | --- |
| UAMS Action Bar | `acf/action-bar` | Text | A bar of links |
| UAMS Call-Out | `acf/call-out` | Text | Highlighted call-out text |
| UAMS CTA Bar | `acf/cta` | Text | A call-to-action bar with a button |
| UAMS Hero | `acf/hero` | Text | A hero image or slideshow |
| UAMS Link List | `acf/link-list` | Text | A list of linked tiles |
| UAMS News | `acf/uams-news` | Text | Syndicated UAMS news; see below |
| UAMS Text & Image Overlay | `acf/text-overlay` | Text | Text and a button on top of an image |
| UAMS Side-by-Side Image & Text | `acf/image-side` | Text | An image on one side and text on the other |
| UAMS Stacked Image & Text | `acf/text-stacked` | Text | An image stacked above text |
| UAMS LiveWhale Calendar | `acf/livewhale-calendar` | Text | A LiveWhale calendar widget |
| UAMS Gallery | `acf/uams-gallery` | Text | An image gallery with a lightbox |
| UAMS Counter List | `acf/counter-list` | Text | A list of counters |
| UAMS Section | `acf/uams-section` | Text | A section that holds other blocks; see below |
| UAMS iFrame | `acf/uams-iframe` | Embeds | An external page in an iframe; administrators only, see below |
| UAMS Content Block | `acf/uams-content` | Not in the inserter | Deprecated; see below |

The inserter category **Text** is WordPress's `common` category.

## When a block is available

- **UAMS News** is registered only while the UAMS news syndication plugin is active. Without it the block is not in the inserter.
- **UAMS Section** holds headings, paragraphs, embeds, lists, quotes, images, shortcodes, tables, files, and Gravity Forms and Formidable forms. Administrators can also place **UAMS iFrame** inside it.
- **UAMS iFrame** takes an `https://` address only, and when the site sets an approved host list (the `uamswp_iframe_allowed_hosts` filter), only an address on that list. Only administrators can insert it or see its fields; another editor opening a page that already holds one sees the preview and no fields. An address that fails the check shows a placeholder in the editor, and nothing on the published page.
- **On a page using the Marketing Landing Page template**, the inserter offers a shorter list: UAMS Hero and UAMS Counter List are left out, and the core blocks are offered only inside a **UAMS Section**.
- **UAMS Content Block** is deprecated in favor of **UAMS Section**. It is hidden from the inserter, and pages that already use it still show and edit it.

## The same layouts as modules

A page using the **Modules** [page template](Page-templates.md) is built from the **UAMS Modules** field rather than from blocks. Its layouts match most of the blocks above: Content, Action Bar, Call-Out, Call-to-Action (CTA) Bar, Hero, Link List, UAMS News, Text & Image Overlay, Post Category Tile (Single and Double), Side-by-side Image & Text, Stacked Image & Text, LiveWhale Calendar, Gallery and Section. See [Building a page](Building-a-page.md).
