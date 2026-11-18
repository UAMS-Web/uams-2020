# Page and post options

<!-- cspell:ignore landingpage UAMS -->

Written against v1.10.2.

The theme adds these panels to the editor. Which panel appears depends on whether the item is a page or a post, and on the [page template](Page-templates.md).

## Header Options (pages)

Shown on every page except one using the **Marketing Landing Page** template.

**Title Options** sets how the page header is drawn:

| Choice | Value | Further fields |
| --- | --- | --- |
| Normal | `none` (default) | None |
| Hidden | `hidden` | None |
| Graphic | `graphic` | **Graphic Title Cover Image**, **Graphic Title Lead Paragraph** |
| Marketing Landing Page | `landingpage` | Desktop and mobile cover images, a title heading and a lead paragraph |
| Hero | `hero` | **Hero** |

## Marketing Landing Page Header Options (pages)

Shown only on a page using the **Marketing Landing Page** template, in place of **Header Options**. Its **Title Options** offers only **Marketing Landing Page** and **Hero**, with the same marketing fields: **Marketing Landing Page Cover Image (Desktop)**, **Marketing Landing Page Cover Image (Mobile)**, **Marketing Landing Page Title Heading**, **Marketing Landing Page Title Lead Paragraph**, and **Hero**.

## UAMS Page Attributes (pages)

| Field | Effect |
| --- | --- |
| Page is a subsection | Makes the page the top of a subsection. On the page and the pages below it, the site header shows its title, linked to it, and the primary navigation is replaced by a menu of the subsection's pages. Search results are not affected |
| Hide Breadcrumbs | Leaves the breadcrumb trail off the page |
| Hide from auto menus | Leaves the page, and the pages below it, out of the automatically generated menus, and the page out of its parent's list of child pages |
| Hide related page menu (children) | Leaves the list of child pages off the page |
| Menu Title | An alternate title for the page in the automatically generated primary navigation menu. Leave it blank to use the page title |

## Header Options (posts)

**Title Options** for a post:

| Choice | Value | Further fields |
| --- | --- | --- |
| Normal | `none` (default) | None |
| Cover | `cover` | **Cover Image**, and a **Coming Soon** notice |

## UAMS Post Options (posts)

| Field | Effect |
| --- | --- |
| Hide Author | Leaves the author off the post |

## UAMS Modules (pages using the Modules template)

The list of modules that makes up a page using the **Modules** template. See [Building a page](Building-a-page.md).
