<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminReportsController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']
    )->name('dashboard');
});

Route::post('/logout-other-browser-sessions', function (Request $request) {
    // Pastikan Anda mengatur ini agar dapat menghapus sesi lain
    $request->user()->currentAccessToken()->delete();

    return back()->with('status', 'Other browser sessions logged out.');
})->name('other-browser-sessions.logout');



// Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
// Route::middleware(['auth:sanctum', 'verified'])->get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
// Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
// Route::resource('reports', ReportController::class);
// Route::post('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

// Route admin
// Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
//Route::get('/admin/dashboard', [AdminReportsController::class, 'dashboard'])->name('admin.dashboard')->middleware('admin');



Route::middleware(['auth'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
});
Route::middleware('auth')->post('/reports', [ReportController::class, 'store'])->name('reports.store');

// Route Overtime
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/overtimes', [OvertimeController::class, 'index'])->name('overtimes.index');
    Route::get('/overtimes/create', [OvertimeController::class, 'create'])->name('overtimes.create');
    Route::post('/overtimes', [OvertimeController::class, 'store'])->name('overtimes.store');
    Route::get('/overtimes/{overtime}', [OvertimeController::class, 'show'])->name('overtimes.show');
    Route::get('/overtimes/{overtime}/edit', [OvertimeController::class, 'edit'])->name('overtimes.edit');
    Route::put('/overtimes/{overtime}', [OvertimeController::class, 'update'])->name('overtimes.update');
    Route::delete('/overtimes/delete/{overtime}', [OvertimeController::class, 'destroy'])->name('overtimes.destroy');
});

//Route::get('dashboard/pdf', [DashboardController::class, 'pdfReport'])->name('dashboard.pdf');
Route::get('/dashboard/admin.report', [DashboardController::class, 'pdfReport'])->name('admin.report');
Route::get('/dashboard/admin.overime', [DashboardController::class, 'pdfOvertimeReport'])->name('admin.overtime');
Route::get('/dashboard/search', [DashboardController::class, 'search'])->name('admin.search');

//route profile
Route::middleware('auth')->group(function () {
   // Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});




