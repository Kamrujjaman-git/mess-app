<?php

use App\Http\Controllers\AdvancePaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HouseRentController;
use App\Http\Controllers\MaidBillController;
use App\Http\Controllers\MarketExpenseController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\MonthlySummaryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/monthly-summary', [MonthlySummaryController::class, 'index'])->name('monthly-summary.index');

Route::resource('users', UserController::class);
Route::resource('expenses', MarketExpenseController::class)
    ->parameters(['expenses' => 'market_expense']);
Route::resource('meals', MealController::class);
Route::resource('advance-payments', AdvancePaymentController::class);

Route::resource('house-rents', HouseRentController::class)->only(['index', 'create', 'store']);
Route::resource('maid-bills', MaidBillController::class)->only(['index', 'create', 'store']);
