# Software Design Description (SDD)

**System:** IoT-ra Lab website
**Baseline:** commit `af3c109`, 2026-10-20
**Structure:** loosely follows IEEE 1016 (design viewpoints)

> **Update 2026-10-21:** the controllers have been reorganised by content type, with the same
> URLs and behaviour ([ADR-0009](adr/0009-controllers-by-content-type.md)). This document
> describes the baseline layout. For the old-to-new mapping, see
> [legacy/controllers/README.md](../legacy/controllers/README.md); for the current
> structure, see the [README](../README.md#system-structure).

---

## 1. Introduction

### 1.1 Purpose
This SDD describes how the system is built: its modules, their interfaces, data
and behaviour. Engineers can use it to change the code safely.

### 1.2 Scope
It covers all application code under `app/`, `routes/`, `database/migrations/`
and `resources/views/`. Framework scaffolding (Jetstream components, Fortify
actions) is described only where it interacts with the custom code.

### 1.3 Related documents
[SRS](SRS.md) · [Architecture](architecture.md) · [System design](system-design.md) · [ADRs](adr/README.md)

---

## 2. Design overview

| Layer | Location | Contents |
|---|---|---|
| Routing | `routes/web.php` | ~70 hand-written routes using string-style `Controller@method` handlers |
| Controllers | `app/Http/Controllers/` | 7 custom controllers, grouped by operation type |
| Models | `app/Models/` | 8 table-binding models plus `User` |
| Views | `resources/views/` | ~60 Blade templates: public pages, admin pages, Jetstream scaffold |
| Schema | `database/migrations/` | 5 framework + 23 custom migrations |
| Static assets | `public/css`, `public/js`, `public/lib` | Public theme (Bootstrap template) |
| Build | `vite.config.js`, `tailwind.config.js` | Tailwind/Jetstream bundle only |

The design is an MVC monolith, transaction-script style: every controller
method reads the request, does any uploads, writes with `DB::table()`, and
redirects with a flash message.

---

## 3. Module design

### 3.1 `show_data`: public read model
| Method | Route | Data passed to view | View |
|---|---|---|---|
| `fetch_home` | `/` | `contents`, `leader`, `partners`, `randomPhotos` (renders even when `contents` is empty; fix 016) | `index` |
| `fetch_team` | `/about` | `teams` (researchers + leader), `PhDs`, `masters`, `admins`, `interns`, `partners`, `contents`, `randomPhotos` | `about_us` |
| `fetch_projects` | `/projects` | `onprojects` (all projects ⨝ photos) | `projects` |
| `fetch_publications` | `/publications` | `publications`, `prototypes` (⨝ photos) | `publications` |
| `fetch_events` | `/news` | `onevents` (left join, grouped by id), `pastevents` | `news` |
| `fetch_partners` | `/partners` | `partners` | `partners` |
| `fetch_gallery`, `fetch_*_gallery` | `/gallery`, `/*_gallery` | entity ⨝ photos grouped by id | `*_gallery` |

Every method also loads `contents` (`website_content::all()`) and `randomPhotos`
(6 random members) for the shared layout partials.

**Defect D1:** `fetch_team` builds
`role='researcher' AND status='Active' OR role='leader'`, so an inactive leader
is still shown (`show_data.php:26-29`). Fix it with a grouped `where` closure.

### 3.2 `retrieve_data`: admin read model
Mirrors `show_data` for the admin listing pages (`homecontent`,
`aboutuscontent`, `projectscontent`, `publicationscontent`, `newscontent`,
`registerprototype`, `oldteam`). These are the pages where admins see records
and open the edit forms.

### 3.3 `insert_data`: create operations
| Method | Route | Inputs | Writes | Upload dir |
|---|---|---|---|---|
| `insert_admin` | `POST /registeradmin` (**public**) | name, email, password, password_confirmation | `users` (plaintext password) | — |
| `insert_home_form1` | `/insertmission` | labname, motto, content (→ vision) | upsert `website_content` | — |
| `insert_contacts` | `/insertcontacts` | logo*, address, telephone, email, socials | upsert `website_content` | `images/logo` |
| `insert_partner` | `/insertpartner` | image*, name, description, website, admin | `partners` | `images/partners` |
| `insert_news` | `/insert_news` | title, start/end date, description, venue, link, images[], admin | `events`, `event_photos` × n | `images/events` |
| `insert_project` | `/submit-project` | image, projectName, venue, participants, description, dates, project_link, admin | `projects`, `project_photos` | `images/projects` |
| `insert_publication` | `/insert_publication` | photo*, file* (csv/pdf/doc/x/xls/x), title, author, date_published, type, description, admin | `publications` | `images/publications`, `documents/publications` |
| `insert_team` | `/insert_team` | image*, firstname, lastname, email, phone, designation, role, bio, admin | `members` | `images/team` |
| `insert_prototype` | `/insertprototype` | image, title, description, project_id, admin | `prototypes`, `prototype_photos` | `images/prototypes` |
| `insert_message` | `POST /insert_message` (**public**) | name, email, phone, message | `messages` | — |

\* = validated as required.

**Defect D2:** `insert_news` and `insert_prototype` find the new row's id with
`Model::latest()->first()`. Under concurrent inserts this can return the wrong
row. Use `insertGetId` or Eloquent `create()`.
**Defect D3:** in `insert_prototype`, `$picname` is undefined when no image is
sent, which causes a PHP error.
**Defect D4:** `insert_admin` stores the raw password and skips Fortify's
password rules.

### 3.4 `edit_data`: update operations
| Method | Route | Updates |
|---|---|---|
| `edit_logo` / `edit_banner` | `/editlogo_route`, `/editbanner_route` | `website_content.logo` / `.banner` |
| `edit_leader` | `/editleader_route` | `website_content.team_leader_word`, photo of every `members` row with `role='leader'` |
| `edit_mission` | `/editmission_route` | Labname, motto, vision, mission, objectives |
| `edit_topbar` | `/edittopbar_route` | social links |
| `edit_labcontacts` | `/editlabinfo_route` | address, email, phone |
| `edit_partner` | `/editpartner_route` | name, logo (**saved to `images/events`**, defect D5) |
| `edit_project` | `/editproject_route` | fields + `project_photos.photo` |
| `edit_event` | `/editnews_route` | fields + every `event_photos.photo` for the event set to one file. **Image always required** (defect D6) |
| `edit_publication` | `/editpublication_route` | fields, optional file and photo |
| `edit_team` | `/editmember_route` | member fields, optional photo |

All edit methods take the record `id` from a form field and run no existence
or authorisation check, so any authenticated admin can edit any record.

### 3.5 `delete_stuff` / `restore_stuff`
These only work on members, as a soft flag on `members.status`. They read
`user_id` from the query string of a POST request.

### 3.6 `iot_admin_login`
| Method | Route | Behaviour |
|---|---|---|
| `login` | `POST /mylogin`, `GET /logmein` | Plaintext comparison, `Auth::login`, `Session::put('id')`, redirect `/dashboard` |
| `logmein` | `GET /oldlogin` (route name `login`, the target of the `auth` middleware redirect) | Render the login form |
| `registerme` | `GET /register`, `GET /registeradminform` | Render the admin registration form |

### 3.7 Models
`members`, `news` (→ `events`), `partners`, `projects`, `prototypes`,
`publications` (casts `date_published`), `event_photos`, `website_content`.
Each only declares `$table`. None defines relationships, `$fillable` or
`$guarded`. `User` is the Jetstream default (HasApiTokens,
TwoFactorAuthenticatable, HasProfilePhoto).

### 3.8 Views
| Group | Files | Layout |
|---|---|---|
| Public | `index`, `about_us`, `projects`, `publications`, `news`, `partners`, `gallery`, `*_gallery`, `policy`, `terms` | `@include('topbar'/'navbar'/'footer')`, Bootstrap 5 |
| Admin list | `homecontent`, `aboutuscontent`, `projectscontent`, `publicationscontent`, `newscontent`, `oldteam` | `@include('sidebar')`, Tailwind/Flowbite, DataTables |
| Admin forms | `register_*`, `registerprototype`, `edit*` | `@include('sidebar')` |
| Auth | `oldlogin`, `register`, `auth/*` (Jetstream) | standalone |
| Scaffold | `welcome`, `dashboard` (placeholder), `profile/*`, `api/*`, `components/*`, `layouts/*`, `navigation-menu` | Jetstream |

Forms include `<input type="hidden" name="admin" value="{{ Session('id') }}">`,
and the controllers trust this value (defect D7).

---

## 4. Data design
See [system-design.md §1](system-design.md#1-data-model) for the ERD and table
usage. Design-level rules the code relies on:
- `website_content` holds one row, and upserts check `first()`.
- At most one member has `role='leader'`.
- Photos are stored as bare filenames, and views prepend the directory.

---

## 5. Interface design
- **HTTP:** HTML form posts (`application/x-www-form-urlencoded` or
  `multipart/form-data`) with a CSRF token, each answered by a 302 redirect
  plus a session flash (`success`, `message`, `greeting`).
- **Programmatic API:** none of note. `/api/user` is unused scaffold.

---

## 6. Known defects and technical debt

This section is the design-level list. For crash analysis, the malware findings and
triage order, see [defects-and-malware-report.md](defects-and-malware-report.md).

| ID | Severity | Location | Description |
|---|---|---|---|
| S0 | Critical | `insert_data.php:209-213` | `insert_news` saves `images[]` with no type validation, under client filenames, in `public/`. Combined with S2, this gives an anonymous visitor a way to put arbitrary files on the server. |
| D10 | High | admin list views (e.g. `projectscontent.blade.php:83`) + `edit*.blade.php` | Edit links put field values in the URL without URL-encoding. Any `&` or `#` in the content makes `$_GET[...]` undefined, so the edit page returns HTTP 500. |
| D11 | Low | `publicationscontent.blade.php:80` | Links to `/editprototype`, which has no route (404) |
| S1 | Critical | `public/lib/owlcarousel/assets/iot_website-v1.5.zip`, `README.md` | Malware loader committed and linked (ADR-0008) |
| S2 | Critical | `routes/web.php:42-44` | Public admin registration |
| S3 | Critical | `insert_data.php:41`, `iot_admin_login.php:22` | Plaintext passwords |
| S4 | High | `routes/web.php:38` | `GET /logmein` puts credentials in URLs and logs |
| S5 | High | all uploads | Client filenames trusted, overwrite collisions |
| S6 | Medium | 10 Blade views using `{!! !!}` | Stored XSS from admin-entered content |
| S7 | Medium | hidden `admin` fields | Spoofable ownership ids |
| S8 | Medium | `/insert_message` | No validation or throttling |
| D1 | Medium | `show_data.php:26-29` | `orWhere` precedence bug |
| D2 | Low | `insert_news`, `insert_prototype` | `latest()` race |
| D3 | Medium | `insert_prototype` | Undefined `$picname` |
| D4 | — | see S3 | — |
| D5 | Low | `edit_data.php:162` | Partner logo saved to `images/events` |
| D6 | Medium | `edit_data.php:237` | Event edit always requires an image, and overwrites all of the event's photos with one file |
| D7 | — | see S7 | — |
| D8 | Low | `routes/web.php` | Dead `Route::view` registrations shadowed by `Route::get` |
| D9 | Low | inner joins in `show_data` | Records without photos are hidden |
| T1 | Debt | controllers | Verb-grouped controllers, no FormRequests, no relationships |
| T2 | Debt | `public/lib` vs `resources/lib` | Duplicated vendor JS/CSS |
| T3 | Debt | `tests/` | No tests for custom code. Jetstream tests likely fail against the custom login and register routes. |
| T4 | Debt | `README.md`, `installation.txt` | No real project README |
| T5 | Debt | `.gitignore` | `composer.lock` and `package-lock.json` are ignored but tracked, so builds aren't reproducible by policy |
| T6 | Debt | schema | Unused tables (scholarships, careers, …); `career_applications.project_id` should probably reference careers |

---

## 7. Testing approach (recommended)
1. Feature tests per route group: public pages return 200 with seeded data;
   admin routes redirect guests; each create, edit and delete flow persists
   correctly.
2. Use `Storage::fake('public')` once uploads move to the `Storage` disk (ADR-0007).
3. Regression tests for every defect above before fixing it.
4. Run `php artisan test` in CI on every push.
