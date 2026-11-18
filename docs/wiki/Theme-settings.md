# Theme settings

<!-- cspell:ignore Multisite UAMS -->

Written against v1.10.2.

The theme adds one settings page to the dashboard, **UAMS Settings**, for everything that applies to the whole site. Only users who can manage options (administrators) see it. It holds three groups of fields: **Theme Settings**, **Phone** and **Tag Manager**.

## Theme Settings

**Theme Style** decides which brand the site carries, and the fields below it change with the choice.

| Theme Style | Value | Further fields |
| --- | --- | --- |
| UAMS.edu | `uams` (default) | **Site Location** |
| UAMS Health | `health` | **Organization** |
| Inside | `inside` | **Organization** |
| Institute | `institute` | **UAMS Institute**, then the department list for the institute chosen; **Custom Addresses** |

**Site Location** (UAMS.edu only):

| Choice | Value | Further fields |
| --- | --- | --- |
| Main Campus | `uams` | **Organization**, then the department list for the college or unit chosen |
| Northwest Campus | `nw-campus` | **Custom Addresses** |
| Regional Campus | `regional-campus` | **Regional Campus**, **Custom Addresses** |
| Institutional Advancement | `advancement` | **Custom Addresses** |

The department lists come in two sets:

- **Under Organization (Main Campus):** one list per college: Health Professions, Medicine, Nursing, Pharmacy, Public Health and the Graduate School. **Other Site (Multisite)** is offered for a site that fits none of them, and shows an **Other (Multisite)** list.
- **Under UAMS Institute (Institute):** one list per institute: Aging, Eye, Spine and Neurosciences, Digital Health and Innovation, Psychiatric Research, Translational Research, Cancer, and Community Health Innovation.

**Custom Addresses** turns on a **Custom Address** list. Each entry has **Address Name**, **Address**, **Address (2)**, **City**, **State** and **Zip**.

## Phone

These fields override the phone label and number in the site footer. Left blank, the footer shows the theme's default label and number, which are the same on every site.

| Field | Effect |
| --- | --- |
| Phone Text | The label shown with the primary number |
| Phone Number | The primary number |
| Secondary Number Type | **None**, **Phone** or **Link**: adds a second number or a link |
| Secondary Link Text, Additional Link | The text and address of a secondary link |
| Secondary Phone Text, Secondary Phone Number | The label and number of a secondary phone |

## Tag Manager

| Field | Effect |
| --- | --- |
| Disable GTM | Turns the Google Tag Manager container off for the site |
| GTM Container ID | Overrides the default container. Leave it blank for the default. Example: `GTM-XXXXXXX` |
