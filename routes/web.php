<?php

use App\Http\Controllers\SlotJobController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SlotJobController::class, 'index'])->name('home');
Route::post('/slot-jobs', [SlotJobController::class, 'store'])->name('slot-jobs.store');
