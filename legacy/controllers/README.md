# Legacy controllers

These are the controllers from before the October 2026 reorganisation. They're kept for
reference only: nothing loads them, because Composer autoloads only `app/`. Routes point to the
controllers in `app/Http/Controllers/`, one per content type:

| Old (method) | New |
|---|---|
| `show_data@fetch_*` | `PublicPageController@fetch_*` (same method names) |
| `retrieve_data@fetch_home`, `insert_data@insert_home_form1`, `insert_contacts`, `edit_forms@edithome` … `editleader`, `edit_data@edit_mission` … `edit_labcontacts` | `HomeContentController` |
| `insert_data@insert_partner`, `edit_forms@editpartner`, `edit_data@edit_partner` | `PartnerController` |
| `retrieve_data@fetch_events`, `insert_data@insert_news`, `edit_forms@editnews`, `edit_data@edit_event` | `EventController` |
| `retrieve_data@fetch_projects`, `insert_data@insert_project`, `edit_forms@editproject`, `edit_data@edit_project` | `ProjectController` |
| `retrieve_data@fetch_publications`, `insert_data@insert_publication`, `edit_forms@editpublications`, `edit_data@edit_publication` | `PublicationController` |
| `retrieve_data@fetch_project_prototype`, `insert_data@insert_prototype` | `PrototypeController` |
| `retrieve_data@fetch_team`, `fetch_oldteam`, `insert_data@insert_team`, `edit_forms@editteam`, `edit_data@edit_team`, `delete_stuff`, `restore_stuff` | `MemberController` |
| `iot_admin_login@registerme`, `insert_data@insert_admin` | `AdminAccountController` |
| `insert_data@insert_message` | `MessageController` |

Every URL stayed the same. `tests/Feature/Refactor/AdminWritesTest.php` checked that each form
behaves exactly as before.
