// ... existing code ...
// Add this near your auth routes
Route::middleware(['web'])->group(function () {
    // Your auth routes (login, register, etc.)
    // This ensures the web middleware group (which includes CSRF protection) is applied
});
// ... existing code ...