<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [CompanyController::class, 'index'])->name('companydetails');
Route::post('add-company', [CompanyController::class, 'store'])->name('addcompany');
Route::get('view-report/{id}', [CompanyController::class, 'show'])->name('viewreport');
