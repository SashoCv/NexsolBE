<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\MailboxController;
use App\Http\Controllers\Admin\ProjectController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
    });

    // Authenticated
    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Leads
        Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
        Route::get('leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
        Route::put('leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
        Route::delete('leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');

        // Projects
        Route::resource('projects', ProjectController::class)->except('show');

        // Mailbox (read-only IMAP inbox)
        Route::get('mailbox', [MailboxController::class, 'index'])->name('mailbox.index');
        Route::get('mailbox/{uid}', [MailboxController::class, 'show'])->whereNumber('uid')->name('mailbox.show');
        Route::get('mailbox/{uid}/attachment/{index}', [MailboxController::class, 'attachment'])
            ->whereNumber('uid')->whereNumber('index')->name('mailbox.attachment');
    });
});
