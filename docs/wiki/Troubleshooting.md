# Troubleshooting

<!-- cspell:ignore UAMS -->

Written against v1.10.2.

- [No UAMS blocks, settings or options appear](#no-uams-blocks-settings-or-options-appear)
- [The UAMS News block is not in the inserter](#the-uams-news-block-is-not-in-the-inserter)
- [The UAMS iFrame block shows no fields](#the-uams-iframe-block-shows-no-fields)
- [The UAMS iFrame block shows a placeholder instead of the page](#the-uams-iframe-block-shows-a-placeholder-instead-of-the-page)
- [The UAMS Content Block is not in the inserter](#the-uams-content-block-is-not-in-the-inserter)
- [The dashboard asks to install plugins](#the-dashboard-asks-to-install-plugins)
- [The UAMS Settings page is missing from the dashboard](#the-uams-settings-page-is-missing-from-the-dashboard)

## No UAMS blocks, settings or options appear

**Cause:** Advanced Custom Fields PRO is not active. The blocks, the **UAMS Settings** page and every option panel are registered through it.

**Fix:** install and activate it. See [Requirements](Requirements.md).

## The UAMS News block is not in the inserter

**Cause:** the block is registered only while the UAMS news syndication plugin is active.

**Fix:** activate that plugin, then reload the editor.

## The UAMS iFrame block shows no fields

**Cause:** only administrators see the block's fields. Another editor opening a page that holds one sees the preview and nothing to edit.

**Fix:** ask an administrator to edit the block.

## The UAMS iFrame block shows a placeholder instead of the page

The block shows `UAMS iFrame: the URL is missing, not https, or not on the approved host list.`

**Cause:** the address is empty, does not start with `https://`, or names a host outside the site's approved list.

**Fix:** enter an `https://` address on an approved host. Example: `https://example.org/embed`.

## The UAMS Content Block is not in the inserter

**Cause:** the block is deprecated and hidden from the inserter. Pages that already hold one still show and edit it.

**Fix:** use **UAMS Section** for new content. See [Blocks](Blocks.md).

## The dashboard asks to install plugins

**Cause:** a plugin the theme requires is missing or inactive. The notice comes from TGM Plugin Activation.

**Fix:** install and activate each plugin the notice names. The list is in [Requirements](Requirements.md).

## The UAMS Settings page is missing from the dashboard

**Cause:** the page is shown only to users who can manage options, and only while Advanced Custom Fields PRO is active.

**Fix:** sign in as an administrator, and check the plugin is active.
