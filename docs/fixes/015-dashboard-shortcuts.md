# Fix 015: Working dashboard shortcuts, and blue everywhere

- **Report IDs:** W20 (empty dashboard); finishes the colour change from fix 014
- **Date:** 2026-10-21

## 1. Dashboard shortcuts
**Before:** the dashboard showed 14 grey boxes with a "+" that did nothing. They were plain
text left over from the Flowbite template, not links.

**Now** (`resources/views/dashboard.blade.php`) the boxes are working shortcuts in two
groups:

| Add content ("+") | Opens |
|---|---|
| Add a project | `/register_projects` |
| Add a news item or event | `/register_news` |
| Add a publication | `/register_publications` |
| Add a prototype | `/registerprototype` |
| Add a team member | `/register_team` |
| Add a partner | `/register_home#partners` |

| Site settings | Opens |
|---|---|
| Lab information · Logo · Banner photo · Contacts · Social links · Leader's message | The edit page for the home-content record (`/edithome?id=…` etc.) |
| Add another admin | `/register` |
| Past team | `/oldteam` |
| Profile & security | `/user/profile` |

On a fresh install with no home content yet, the dashboard says so, and the settings
shortcuts lead to **Add New Home Content**. Nothing about how content is added changed.
Each shortcut opens the same form as the sidebar.

## 2. No green left anywhere, and no indigo
A scan of every live template and stylesheet looked for any green hue: hex, `rgb()`, colour
names, Tailwind `green/emerald/lime/teal` classes and Bootstrap `*-success` classes. It
found and replaced:
- **Add a project / Add a news item forms:** inline CSS button colours `#4CAF50` / `#3e8e41`,
  now brand navy `#034486` / `#04366B`.
- **Admin Projects and News lists:** 12 Bootstrap `text-success` labels, now `text-brand-700`.
- **Jetstream profile and auth pages:** 8 green confirmation texts, plus 40 indigo accents
  (buttons, focus rings, links), all now `brand-*`. The Vite-built Tailwind got the same
  `brand` palette in `tailwind.config.js`.
- **Public contact form:** the "Thanks" confirmation colours, now light brand blue.

Kept on purpose:
- The legacy templates (`resources/views/legacy/`, `navbar`, `footer`, `topbar`) are left as
  they were.
- Error messages stay red.

## Verification
- `tests/Feature/Fixes/DashboardShortcutsTest.php` covers: every add shortcut, every
  settings shortcut pointing at the record and opening, and the fresh-install fallback.
  The suite now has 75 passing tests.
- The green scan returns 0.
- Checked in Chrome: dashboard → "Add a project" opens the form, the Add Project button is
  navy, and the profile page uses the brand blue.
