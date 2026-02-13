<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Kiosk;
use App\Livewire\DisplayMonitor;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Categories as AdminCategories;
use App\Livewire\Admin\Tellers as AdminTellers;
use App\Livewire\Admin\Settings as AdminSettings;
use App\Livewire\Admin\Tickets as AdminTickets;
use App\Livewire\Admin\Reports as AdminReports;
use App\Livewire\Teller\Dashboard as TellerDashboard;
use App\Livewire\Teller\MyQueue as TellerMyQueue;

Route::get('/', function () {
    return redirect()->route('login');
});

// Public routes
Route::get('/kiosk', Kiosk::class)->name('kiosk');
Route::get('/display', DisplayMonitor::class)->name('display');

// Authenticated routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isTeller()) {
            return redirect()->route('teller.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    // Admin routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('/categories', AdminCategories::class)->name('categories');
        Route::get('/tellers', AdminTellers::class)->name('tellers');
        Route::get('/settings', AdminSettings::class)->name('settings');
        Route::get('/tickets', AdminTickets::class)->name('tickets');
        Route::get('/reports', AdminReports::class)->name('reports');
    });

    // Teller routes
    Route::middleware(['teller'])->prefix('teller')->name('teller.')->group(function () {
        Route::get('/dashboard', TellerDashboard::class)->name('dashboard');
        Route::get('/my-queue', TellerMyQueue::class)->name('my-queue');
    });
});
