<?php

declare(strict_types=1);

use App\Enums\RoleEnum;
use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:'.RoleEnum::ADMIN->value])->prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
