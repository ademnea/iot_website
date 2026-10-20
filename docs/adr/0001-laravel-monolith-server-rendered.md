# ADR-0001: Laravel 10 monolith with server-rendered Blade

- **Status:** Accepted (retroactive)
- **Date:** 2023-04 (recorded 2026-10-20)

## Context
The lab needs a public site with content that non-developers can edit. The team was
small, the hosting was a single PHP-capable server, and the team already knew PHP.

## Decision
Build one Laravel 10 application. Blade renders both the public site (Bootstrap 5 theme)
and the admin console (Tailwind + Flowbite). MySQL is the database. Jetstream/Livewire
were installed for the auth scaffolding.

## Consequences
- ✅ One deployable unit and simple hosting. No SPA build step is needed for the public site.
- ✅ Good SEO with no extra work.
- ❌ Two CSS frameworks and many CDN dependencies, so styling is inconsistent and pages
  depend on third parties at runtime.
- ❌ Jetstream brings features (teams, API tokens, 2FA) that the custom code doesn't
  integrate with (see ADR-0003).

## Alternatives considered
Static site generator + headless CMS (rejected: needs extra services); WordPress (not
recorded; plausible alternative for a content site).
