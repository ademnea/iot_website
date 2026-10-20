# Software Requirements Specification (SRS)

**System:** IoT-ra (Internet of Things - Research & Applications) Lab website (`iot_website`)
**Version:** 1.0 (as-built baseline)
**Date:** 2026-10-20

---

## 1. Introduction

### 1.1 Purpose
This SRS describes the requirements the current system meets, plus the gaps
found during review. It is the reference for upcoming maintenance and feature
work.

### 1.2 Scope
The system is a public, content-managed website for the **Makerere University
IoT Lab** (CoCIS, Block B). It has two parts:

1. **Public site.** Visitors browse information about the lab: mission and
   vision, team, projects, prototypes, publications, news and events, partners,
   and galleries. They can also send a contact message.
2. **Admin console.** Authenticated administrators create and edit everything
   the public site shows, without touching code.

The database schema also contains tables for scholarships, careers, applicants
and newsletters. None of these has a UI or controller yet (see §3.9).

### 1.3 Definitions
| Term | Meaning |
|---|---|
| Admin | A row in `users`. Can log in to the console. |
| Member | A lab person shown on the site (`members` table). Members are not users. |
| Website content | The single-row `website_content` table holding lab name, mission, vision, logo, banner, contacts and social links. |
| Event / News | Rows in `events` (Eloquent model `news`). |
| Ongoing / Past | Status enum used by projects and events. |

### 1.4 References
- `DB_mapping.txt`: original schema design notes
- `installation.txt`: setup steps
- Laravel 10, Jetstream 3 / Fortify, Livewire 2 documentation

---

## 2. Overall Description

### 2.1 Product perspective
A standalone Laravel 10 monolith with server-rendered Blade views and a MySQL
database. There are no integrations with external systems. Uploaded media is
stored on the web server's local disk under `public/`.

### 2.2 User classes
| Actor | Description | Access |
|---|---|---|
| Visitor | Anonymous member of the public | Public pages, contact form |
| Admin | Lab staff maintaining content | Everything in the console |
| System operator | Deploys and maintains the server | Server, DB, `.env` |

### 2.3 Operating environment
- PHP ≥ 8.1, Composer, Node/npm (Vite 4)
- MySQL (per `.env.example`); sessions stored in the database
- Any web server that can serve `public/` (Apache `.htaccess` included)
- Modern desktop and mobile browsers. Bootstrap 5, Tailwind and Flowbite are
  loaded from CDNs.

### 2.4 Constraints
- Content must be editable by non-developers.
- Hosting is a single server; there is no object storage or CDN for uploads.
- Front-end libraries come from public CDNs, so pages need internet access to
  render correctly.

### 2.5 Assumptions
- Exactly one `website_content` row exists. The home page redirects to login
  when there is none (`show_data@fetch_home`).
- Exactly one member has `role = 'leader'`.

---

## 3. Functional Requirements

IDs: `FR-<area>-<n>`. **Status** is *Implemented*, *Partial* (works with
defects), or *Not implemented*.

### 3.1 Public home (`/`)
| ID | Requirement | Status |
|---|---|---|
| FR-HOME-1 | Show lab name, motto, vision, mission, objectives, banner and logo from `website_content`. | Implemented |
| FR-HOME-2 | Show the team leader's photo and message. | Implemented |
| FR-HOME-3 | Show partners. | Implemented |
| FR-HOME-4 | When no website content exists, redirect to the admin login so first-run setup can happen. | Implemented |

### 3.2 About / team (`/about`)
| ID | Requirement | Status |
|---|---|---|
| FR-TEAM-1 | List active members grouped by role: leader and researchers, PhD students, Masters students, administrators, interns. | Partial: the leader is shown even when inactive, because of an `orWhere` precedence bug (`show_data.php:28`) |
| FR-TEAM-2 | Visitors can submit a contact message (name, email, phone, message). | Implemented, with no validation, rate limit or spam protection |

### 3.3 Projects (`/projects`)
| ID | Requirement | Status |
|---|---|---|
| FR-PROJ-1 | List projects with photo, venue, participants, description, dates and website link. | Implemented. Projects without a `project_photos` row are hidden (inner join). |
| FR-PROJ-2 | Separate ongoing and past projects. | Partial: only the admin view splits them. There is no UI for changing status. |

### 3.4 Publications and prototypes (`/publications`)
| ID | Requirement | Status |
|---|---|---|
| FR-PUB-1 | List publications (title, type, author, date, description, cover photo, downloadable file). | Implemented |
| FR-PUB-2 | List prototypes with photos. | Implemented |

### 3.5 News and events (`/news`)
| ID | Requirement | Status |
|---|---|---|
| FR-NEWS-1 | List ongoing events with all their photos. | Implemented |
| FR-NEWS-2 | List past events. | Partial: no UI sets status to `Past` |

### 3.6 Partners and galleries
| ID | Requirement | Status |
|---|---|---|
| FR-PART-1 | `/partners` lists partners with logo, description and link. | Implemented |
| FR-GAL-1 | `/gallery`, `/project_gallery`, `/team_gallery`, `/prototypes_gallery` and `/events_gallery` show photo galleries. | Implemented |

### 3.7 Authentication
| ID | Requirement | Status |
|---|---|---|
| FR-AUTH-1 | Admins log in with email and password. | Implemented, insecurely: plaintext comparison (`iot_admin_login.php:22`) |
| FR-AUTH-2 | Only an existing admin may create another admin. | **Not met**: `/register` and `/registeradmin` are public (`routes/web.php:42-44`) |
| FR-AUTH-3 | Admins can log out. | Implemented (Fortify `logout` route) |
| FR-AUTH-4 | Password reset, 2FA and profile management. | Present via Jetstream/Fortify, but incompatible with plaintext passwords (see ADR-0003) |

### 3.8 Admin content management (all behind `auth`)
| ID | Requirement | Status |
|---|---|---|
| FR-ADM-1 | Edit lab name, motto, vision, mission and objectives. | Implemented |
| FR-ADM-2 | Change logo and banner. | Implemented |
| FR-ADM-3 | Edit contacts (address, phone, email) and social links. | Implemented |
| FR-ADM-4 | Edit the leader's message and photo. | Implemented |
| FR-ADM-5 | Add and edit partners. | Implemented (edit stores logos under `images/events`, not `images/partners`, so edited logos may not display) |
| FR-ADM-6 | Add and edit projects with a photo. | Implemented |
| FR-ADM-7 | Add and edit events with several photos. | Partial: editing always requires a new image (`edit_data.php:237`) |
| FR-ADM-8 | Add and edit publications (cover photo and document). | Implemented |
| FR-ADM-9 | Add prototypes linked to a project. | Partial: fails without an image (`$picname` undefined) |
| FR-ADM-10 | Add and edit team members. | Implemented |
| FR-ADM-11 | Deactivate ("delete") and restore members. | Implemented as a soft status flag |
| FR-ADM-12 | Delete projects, events, publications, partners and prototypes. | Not implemented |
| FR-ADM-13 | View contact messages. | Not implemented: messages are stored but never shown |
| FR-ADM-14 | Dashboard with summary stats. | Not implemented (placeholder grid) |

### 3.9 Schema present, no feature yet
Scholarships, careers, applicants, scholarship and career applications,
newsletters with photos, member phones, project members and member
publications. These are candidates for future scope.

---

## 4. Non-Functional Requirements

| ID | Category | Requirement | Current state |
|---|---|---|---|
| NFR-SEC-1 | Security | Passwords must be stored with a one-way adaptive hash (bcrypt/argon2). | **Violated** |
| NFR-SEC-2 | Security | Admin creation must require an authenticated admin. | **Violated** |
| NFR-SEC-3 | Security | Credentials must never travel in a URL query string. | **Violated**: `GET /logmein` accepts them |
| NFR-SEC-4 | Security | Uploaded files must get server-generated names and be type-checked by content. | **Violated**: client filenames are used and silently overwrite existing files |
| NFR-SEC-5 | Security | Rich-text output must be sanitised before unescaped rendering (`{!! !!}`). | Not sanitised (about_us, index, news, galleries) |
| NFR-SEC-6 | Security | Server-side ownership fields (`admin_id`, `registered_by`) must come from the session, not from form input. | **Violated**: taken from a hidden `admin` field |
| NFR-SEC-7 | Security | All state-changing forms use CSRF protection. | Met (`@csrf` in forms, `VerifyCsrfToken` middleware) |
| NFR-SEC-8 | Security | Public forms must be rate-limited. | Not met |
| NFR-SEC-9 | Supply chain | The repository must contain no executable or third-party binaries that are not part of the app. | **Violated**: malicious zip (see `docs/README.md`) |
| NFR-REL-1 | Reliability | Multi-step writes (record + photos) should be atomic. | Not met: no transactions |
| NFR-REL-2 | Reliability | Invalid input yields a validation message, never a 500. | Partial |
| NFR-PERF-1 | Performance | Public pages render in < 1 s on the target host with typical data (< 500 rows per table). | Likely met. `inRandomOrder()` on every page is fine at this scale. |
| NFR-MAINT-1 | Maintainability | Controllers follow resource conventions and PSR naming. | Not met (`insert_data`, `edit_data`, etc.) |
| NFR-MAINT-2 | Maintainability | Automated tests cover custom features. | Not met: only Jetstream scaffold tests exist |
| NFR-USE-1 | Usability | The public site is responsive. | Met (Bootstrap 5) |
| NFR-PORT-1 | Portability | Runs on any LAMP/LEMP host with PHP 8.1+. | Met |

---

## 5. External Interfaces

- **User interface:** Bootstrap 5 public theme. The admin UI uses Tailwind with
  Flowbite and a sidebar layout.
- **Data:** MySQL via Laravel's query builder.
- **Files:** uploads are written to `public/images/{logo,banner,team,partners,events,projects,publications,prototypes}`
  and `public/documents/publications`.
- **API:** `GET /api/user` (Sanctum, scaffold only). Nothing consumes it.
- **Email:** configured (SMTP) but only Fortify password-reset would use it.

---

## 6. Open questions for stakeholders
1. Should admins have roles (for example, content editor vs. super-admin)?
2. Are scholarships, careers and newsletters in scope for the next release?
3. Who should receive contact-form messages: email, the dashboard, or both?
4. Is there a hosting target (shared cPanel, VPS, university server)? This
   determines how storage and deployment can change.
