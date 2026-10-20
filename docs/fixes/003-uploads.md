# Fix 003: Uploads

- **Report IDs:** C1 (upload half), S0, S5, D5 (ADR-0004; first step of ADR-0007)
- **Severity:** Critical
- **Date:** 2026-10-20

## Problem
1. `insert_news` saved every file in `images[]` into `public/images/events/` with no type
   check, under the uploader's own filename. Combined with public admin registration (fix
   002), that let an outsider put arbitrary files, including PHP, into a web-served folder.
2. Every upload kept the client's original filename. Two different records that uploaded
   `photo.jpg` overwrote each other's image.
3. `edit_partner` saved new logos to `images/events/`, but pages load partner logos from
   `images/partners/`, so edited logos never displayed.
4. Nothing stopped the web server from running scripts placed in the upload folders.

## Change
| File | Change |
|---|---|
| `insert_data.php` (`insert_news`) | `images.*` validated as `image|mimes:png,jpg,jpeg|max:11000` before anything is saved. |
| `insert_data.php`, `edit_data.php` | All 17 `getClientOriginalName()` calls replaced with `hashName()`, a random 40-character name plus the extension detected from the file's content. Files stay in the same folders, so views need no change. |
| `edit_data.php` (`edit_partner`) | Saves to `images/partners`. |
| `public/images/.htaccess`, `public/documents/.htaccess` (new) | Deny `.php`, `.phtml`, `.phar`, `.pl`, `.py`, `.cgi`, `.sh`; turn off the PHP engine; `-ExecCGI -Indexes`. |
| `.gitignore` | `public/images/*` and `public/documents/*` with exceptions so the two `.htaccess` files are tracked. |

On **Nginx**, `.htaccess` is ignored. Add an equivalent rule:

```nginx
location ~* ^/(images|documents)/.*\.(php\d?|phtml|phar|pl|py|cgi|sh)$ { deny all; }
```

## Known side effects
- New uploads have random names, so downloaded publication files are named like
  `Xk3…9.pdf` instead of the original title. If that matters, add a column for the original
  name later (ADR-0007).
- A CSV upload is saved as `.txt`, because its detected type is `text/plain`. It still opens
  correctly.
- Existing files keep their old names. Nothing is renamed.

## Verification
`tests/Feature/Fixes/UploadFixesTest.php`:
- a `.php` file in `images[]` is rejected and nothing is written
- two `logo.png` uploads get two different stored names, and both files exist
- an edited partner logo lands in `images/partners`
- both `.htaccess` guards are present
