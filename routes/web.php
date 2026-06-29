<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TicketController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return Inertia::render('Landing', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/about', function () {
        return Inertia::render('About');
    })->name('about');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.update-status');
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->middleware('role:admin')->name('tickets.assign');
    Route::post('/tickets/{ticket}/comments', [TicketController::class, 'addComment'])->name('tickets.comments.store');

    Route::get('/tickets/{ticket}/report/create', [TicketController::class, 'createReport'])->middleware('role:technicien,admin')->name('tickets.report.create');
    Route::post('/tickets/{ticket}/report', [ReportController::class, 'store'])->middleware('role:technicien,admin')->name('reports.store');
    Route::get('/tickets/{ticket}/report/download', [ReportController::class, 'download'])->name('reports.download');
    Route::post('/tickets/{ticket}/evaluate', [EvaluationController::class, 'store'])->middleware('role:client')->name('tickets.evaluations.store');

    Route::get('/tech/profile', [\App\Http\Controllers\TechProfileController::class, 'index'])->middleware('role:technicien')->name('tech.profile');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::get('/notifications/count', [NotificationController::class, 'count'])->name('notifications.count');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/tickets', [AdminController::class, 'tickets'])->name('tickets');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/technician', [AdminController::class, 'storeTechnician'])->name('users.technician');
        Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');
        Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
