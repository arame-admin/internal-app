# UX Improvement: Auto-fill End Date on Start Date Selection

## Status: ✅ COMPLETE

**Request:** On start_date selection → auto-set end_date to same date (single day default). User can change for multi-day.

**Current:** `updateDateRange()` only sets minDate, doesn't auto-fill value.

**Plan:**
Update `updateDateRange()` in `resources/views/User/leaves/apply.blade.php`:
```
function updateDateRange() {
    var startDate = $('#start_date').datepicker('getDate');
    if (startDate) {
        var dateStr = $.datepicker.formatDate('yy-mm-dd', startDate);
        $('#end_date').datepicker('option', 'minDate', startDate);
        $('#end_date').val(dateStr);  // ← Auto-fill
        calculateDays();
    }
}
```

### Step 1: [✅ COMPLETE] Update updateDateRange() function

**Next:** Edit JS function

