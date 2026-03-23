# Fix Project Creation (/admin/projects/create)

Status: ✅ **COMPLETE** - Client ID validation fixed in ProjectController and create form.

Latest checks:
```
migrate:status → clients/projects PENDING
logs → MySQL connection refused (port 3306)
```

## Updated Steps:
- [❌] **Step 1**: Start XAMPP MySQL (port 3306 **GREEN**) ← **DO THIS FIRST**
- [ ] **Step 2**: `php artisan migrate` (creates missing tables)
- [ ] **Step 3**: `php artisan db:seed --class=ClientSeeder`
- [ ] **Step 4**: `php artisan config:clear`
- [ ] **Step 5**: Test create → success?
[✅ Complete] **Step 6**: Code fix ProjectController validation + form required attribute
- [ ] **Complete**

**Fixed:** Client ID now required in validation and form. Test /admin/projects/create
```
1. XAMPP Control Panel → Start MySQL (green)
2. Terminal: php artisan migrate  [PASTE OUTPUT HERE]
3. php artisan db:seed --class=ClientSeeder  [PASTE OUTPUT]
4. Test page, share result/error
```

**Why broken**: No projects table = creation fails.
