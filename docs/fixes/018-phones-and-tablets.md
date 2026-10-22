# Fix 018: Phones and tablets

- **Found by:** a headless-Chrome check of every page at 360 px (phone), 768 px (tablet)
  and 1280 px (desktop)
- **Severity:** Medium: the public menu lost two links on phones, and most admin forms
  were unusable on a phone
- **Date:** 2026-10-22

## Problem
**Public site**
1. On phones the menu scrolled sideways, so **Team & contact** and **Admin login** were
   off-screen. Nothing showed that more links existed.
2. The contact strip took three lines, and the animated tower filled the first screen of
   the home page, so the headline only appeared after scrolling.

**Admin**
3. 15 pages had no viewport tag, so phones drew them at desktop width, zoomed out:
   every edit page, plus Add home content, Add team member, Add prototype and Add
   publication.
4. On Home content, News and Products the sidebar was included twice (two menu buttons,
   two sidebars with the same id).
5. The sidebar's menu button hid at `md`, but the sidebar itself appears at `sm`, so
   between 640 and 768 px both showed.
6. **On phones the menu button did nothing**, so admins could not reach the sidebar.
   It relied on Flowbite's `data-drawer-*` attributes, which the Flowbite 1.4.7 loaded by
   the sidebar does not handle. It only worked on Current Team, which also loads
   Flowbite 2.1.1.
7. Fixed widths: 10% / 5% / 60% side margins and half-width cards squeezed the content
   to about 150 px on a phone. On Home content, the social links were also pushed past the
   left edge.

## Change
- `layouts/public.blade.php`, `resources/css/public.css`:
  - Up to 860 px, the menu folds behind a **Menu** button and opens as a full-width list.
    Escape closes it, and without JavaScript the links stay visible.
  - On phones, the contact strip leaves out the address (it is also in the footer).
  - The tower drawing is 200 px wide.
  - Social icons and footer links are larger to tap.
- Admin views:
  - The viewport tag is added to the 15 pages.
  - The duplicate sidebar include is removed.
  - The sidebar toggle hides at `sm` and is opened by a small script of our own in
    `sidebar.blade.php`. A dimmed backdrop appears behind it, and a tap on the backdrop or
    Escape closes it.
  - The fixed margins and widths now apply only from `sm` up (`sm:mx-[10%]`,
    `w-full sm:w-1/2`).
  - The team leader block on Home content stacks on phones.
- `partials/admin-theme.blade.php` (every admin page includes it), shared rules:
  - Grid columns may shrink.
  - Long links and emails wrap.
  - Images fit their box.
  - On phones, wide tables scroll inside their box.

## Verification
- `ResponsiveLayoutTest`:
  - Every public page and all 25 admin pages declare the viewport.
  - The Menu button is present.
  - The sidebar is included once, with the right breakpoint.
- Headless Chrome, 360 px: the sidebar opens from its button and closes from the backdrop
  on all seven admin list pages checked.
- The headless-Chrome check found no sideways scrolling and no clipped content at 360 and
  768 px. Screenshots were reviewed before and after.
