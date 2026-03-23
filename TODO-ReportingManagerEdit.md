# TODO: Add Reporting Manager Selector to Admin User Edit
Status: ✅ COMPLETED

**Changes Made**:
1. ✅ UserController@edit: Added `$managers` load & compact
2. ✅ edit.blade.php: Added reporting manager select field + fixed duplicate form directives
3. ✅ UserController@update: Added `reporting_manager_id` validation rule
4. ✅ Verified form submission works

**Result**: Reporting manager now updates successfully from admin edit page.

**Current**: Fully functional.

