# Decision log

The canonical record of why the project is the way it is: the reasoning and the rejected alternatives behind decisions that have closed. It guards against silently reopening a settled decision.

**Consult it before acting.** Before proposing a change, reopening a question or picking an approach, check here first. The path under consideration may already be a decided-and-recorded one, or an explicitly rejected one. If it is, follow the record, or make the case to revisit it.

**When a decision closes, record the verdict in both places.** Capture the what, the why and the rejected alternatives here (the cross-ticket index) and in the GitHub issue's `## Decision` section (the per-ticket record), keeping the two consistent. Put the full reasoning in the relevant `docs/` companion when the decision touches a documented area. The issue tracker stays the system of record for a single ticket's status.

## How to add an entry

Entries run oldest first, one per closed decision, so a new entry is appended to the end of the file. Keep it terse; the companion or the issue carries the depth.

Every entry heading is `## YYYY-MM-DD`, a space, and a short title. Leave exactly one blank line above each heading. The check (`decision-log` skill) fails on a heading dated earlier than the one before it, and on a heading that sits directly under the previous line.

```markdown
## YYYY-MM-DD <short decision title> (<#issue or area>)

**Decision.** <what was decided, in a sentence or two>

**Why.** <the reasoning that settled it>

**Rejected.** <the alternatives considered, and why each lost>

**Companion.** <link to the docs/ companion or the GitHub issue holding the full reasoning>
```

---

## 2026-11-10 Keep the sites.uams.edu framing policy in a per-site theme setting (#742)

**Decision.** The frame-ancestors policy and the extra allowed origin that sites.uams.edu needs are read by the theme from one per-site setting, and the theme sends nothing when the setting is unset.

**Why.** It is the only option that covers both pieces (the origin is the `allowed_http_origins` filter, which needs PHP), it lives where this repository's tests and review reach it, and every other install is unchanged by default.

**Rejected.** A must-use plugin: needs its own repository to be versioned and tested, and stays the fallback if sites.uams.edu already manages one. Host configuration: carries the two headers but not the origin, and sits outside every repository.

**Companion.** [UAMS-Web/uams-2020#742](https://github.com/UAMS-Web/uams-2020/issues/742); the build is [UAMS-Web/uams-2020#649](https://github.com/UAMS-Web/uams-2020/issues/649).

## 2026-11-14 Hide the Themes and Widgets menu entries from editors by slug (#783)

**Decision.** Editors do not see the Themes and Widgets entries under Appearance. They are removed by slug with `remove_submenu_page()`, using the same editor-role test the `admin_init` guard uses, and the guard stays as the enforcement. The two `unset` calls on submenu indexes 6 and 15 are dropped. Editors keep Menus and the Customizer.

**Why.** The guard already refuses editors on both screens, so the menu showed links to an error page. Removing by slug hides exactly the two entries nobody wants editors to reach and survives WordPress renumbering the submenu, which is what made the index-based `unset` calls hit the wrong entries (Design, Editor or Patterns, and Header).

**Rejected.** Deleting the dead hook and keeping the guard: editors keep links that lead to a 403. Deleting the hook and the guard: widens editor access with no known need. Capability-based hiding: editors hold `edit_theme_options`, the capability that gates every Appearance entry, so a capability test cannot separate them from administrators. A custom role without `switch_themes`: a larger change that touches every site's users and was not measured.

**Companion.** [UAMS-Web/uams-2020#783](https://github.com/UAMS-Web/uams-2020/issues/783); the build is [UAMS-Web/uams-2020#688](https://github.com/UAMS-Web/uams-2020/issues/688).

## 2026-11-14 Close the Customizer Widgets panel for editors by removing the component (#785)

**Decision.** The `widgets` Customizer component is removed through `customize_loaded_components` for `uamswp_is_restricted_editor()` users, so the Widgets panel, its sidebar sections and its widget settings never register and a forged Customizer save has nothing to write to. Menus (`nav_menus`) and every other component stay available. The build is #794.

**Why.** Editors hold `edit_theme_options`, which gates the whole Widgets panel, its sections and its settings in WordPress 7.0.8 (read in core, not run as an editor). #783 and #688 already refuse the `widgets.php` screen for editors, and the Customizer was a second route to the same widgets. The theme has live widget areas (Genesis `footer-1` and `sidebar`, and the two classes in `lib/widgets.php`), so the panel is active.

**Rejected.** Keeping the panel open to editors: leaves a second route to the screen that #783 refuses. `WP_Customize_Manager::remove_panel('widgets')`: hides the panel and leaves the widget settings registered, so a forged save would still write. Reading the live editor list first: the list does not change what the panel exposes, so it moved to #794 as sizing.

**Companion.** [UAMS-Web/uams-2020#785](https://github.com/UAMS-Web/uams-2020/issues/785); the build is [UAMS-Web/uams-2020#794](https://github.com/UAMS-Web/uams-2020/issues/794).
