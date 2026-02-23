<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Client\BantuanController;
use App\Http\Controllers\Client\BroadcastController as ClientBroadcastController;
use App\Http\Controllers\Client\ContactController as ClientContactController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\MessageTemplateController as ClientMessageTemplateController;
use App\Http\Controllers\Client\SegmentController as ClientSegmentController;
use App\Http\Controllers\Client\WhatsAppController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('client.dashboard');
    }

    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::post('users/{user}/regenerate-api-key', [UserController::class, 'regenerateApiKey'])->name('users.regenerate-api-key');
    Route::resource('clients', ClientController::class)->only(['index', 'create', 'store', 'destroy', 'edit', 'update']);
});

Route::middleware(['auth', 'client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');

    Route::resource('contacts', ClientContactController::class);
    Route::get('contacts-import/form', [ClientContactController::class, 'importForm'])->name('contacts.import-form');
    Route::post('contacts-import', [ClientContactController::class, 'import'])->name('contacts.import');

    Route::resource('segments', ClientSegmentController::class);
    Route::get('segments/{segment}/add-contacts', [ClientSegmentController::class, 'addContacts'])->name('segments.add-contacts');
    Route::post('segments/{segment}/attach-contact', [ClientSegmentController::class, 'attachContact'])->name('segments.attach-contact');
    Route::delete('segments/{segment}/contacts/{contact}', [ClientSegmentController::class, 'detachContact'])->name('segments.detach-contact');

    Route::get('/message-templates', [ClientMessageTemplateController::class, 'index'])->name('message-templates.index');
    Route::get('/message-templates/create', [ClientMessageTemplateController::class, 'create'])->name('message-templates.create');
    Route::post('/message-templates', [ClientMessageTemplateController::class, 'store'])->name('message-templates.store');
    Route::get('/message-templates/{template}', [ClientMessageTemplateController::class, 'edit'])->name('message-templates.edit');
    Route::put('/message-templates/{template}', [ClientMessageTemplateController::class, 'update'])->name('message-templates.update');
    Route::delete('/message-templates/{template}', [ClientMessageTemplateController::class, 'destroy'])->name('message-templates.destroy');
    Route::get('/whatsapp', [WhatsAppController::class, 'index'])->name('whatsapp.index');
    Route::get('/whatsapp/status', [WhatsAppController::class, 'status'])->name('whatsapp.status');
    Route::get('/whatsapp/qr-image', [WhatsAppController::class, 'qrImage'])->name('whatsapp.qr-image');
    Route::post('/whatsapp/logout', [WhatsAppController::class, 'logout'])->name('whatsapp.logout');
    Route::post('/whatsapp/reconnect', [WhatsAppController::class, 'reconnect'])->name('whatsapp.reconnect');

    Route::resource('broadcasts', ClientBroadcastController::class)->only(['index', 'create', 'store', 'show']);

    Route::get('broadcasts/template/csv', [ClientBroadcastController::class, 'downloadCsvTemplate'])->name('broadcasts.template');
    Route::get('broadcasts/template/excel', [ClientBroadcastController::class, 'downloadExcelTemplate'])->name('broadcasts.template-excel');

    Route::get('/bantuan', [BantuanController::class, 'index'])->name('bantuan');
});

require __DIR__.'/auth.php';
