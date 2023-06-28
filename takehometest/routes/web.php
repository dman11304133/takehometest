<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FundController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/funds', [FundController::class, 'index'])->name('funds.index');

Route::get('/funds/create', [FundController::class, 'create'])->name('funds.create');
Route::post('/funds', [FundController::class, 'store'])->name('funds.store');
Route::get('/funds/{id}/edit', [FundController::class, 'edit'])->name('funds.edit');
Route::delete('/funds/{id}', [FundController::class, 'destroy'])->name('funds.destroy');
    Route::get('/funds/potential-duplicates', [FundController::class, 'potentialDuplicates'])->name('funds.potential-duplicates');
Route::get('admin/create-manager', [AdminController::class, 'createManager'])->name('admin.create-manager');
Route::post('admin/store-manager', [AdminController::class, 'storeManager'])->name('admin.store-manager');
Route::get('admin/create-company', [AdminController::class, 'createCompany'])->name('admin.create-company');
Route::post('admin/store-company', [AdminController::class, 'storeCompany'])->name('admin.store-company');
Route::get('funds/{id}/edit', [FundController::class, 'edit'])->name('funds.edit');
Route::put('funds/{fund}', [FundController::class, 'update'])->name('funds.update');
