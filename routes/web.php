<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/users', function () {
            return view('admin.users');
        })->name('users.index');

        Route::get('/roles', function () {
            return view('admin.roles');
        })->name('roles.index');

        Route::get('/permissions', function () {
            return view('admin.permissions');
        })->name('permissions.index');
    });
});