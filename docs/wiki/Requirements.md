# Requirements

<!-- cspell:ignore Lyte maskfield UAMS UAMSWP -->

Written against v1.10.2.

UAMS 2020 is a child theme of the Genesis framework. It needs the following before it works as documented in this wiki.

## WordPress and PHP

| Requirement | Version |
| --- | --- |
| WordPress | 6.8 or later |
| PHP | 8.2 or later |

Both are declared in the theme's `style.css` header (`Requires at least` and `Requires PHP`).

## Parent theme

| Theme | Folder |
| --- | --- |
| Genesis | `wp-content/themes/genesis` |

The child theme's header names `genesis` as its template, so WordPress loads Genesis first. Without it the theme cannot be activated.

## Plugins

The theme asks for these plugins through TGM Plugin Activation, which shows a notice in the dashboard until each required plugin is installed and active.

| Plugin | Slug | Required |
| --- | --- | --- |
| Advanced Custom Fields PRO | `advanced-custom-fields-pro` | Yes |
| Advanced Custom Fields: Mask Field | `acf-maskfield` | Yes |
| Better Image Sizes | `better-image-sizes` | Yes |
| UAMSWP YouTube Lyte | `uamswp-youtube-lyte` | Yes |
| Widget CSS Classes | `widget-css-classes` | No (recommended) |

**Note:** the [UAMS settings](Theme-settings.md), the [page and post options](Page-and-post-options.md) and every [block](Blocks.md) are Advanced Custom Fields field groups and blocks, so none of them appear while Advanced Custom Fields PRO is inactive.

The **UAMS News** block also needs the UAMS news syndication plugin; see [Blocks](Blocks.md).
