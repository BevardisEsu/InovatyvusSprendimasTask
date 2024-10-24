<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\TruckSubunitController;
use App\Http\Controllers\ActivityLogController;
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

Route::get('/', function () {
    return redirect()->route('trucks.index');
});

// Truck routes TruckController
Route::controller(TruckController::class)->group(function () {
    Route::get('/trucks', 'index')->name('trucks.index');
    Route::get('/trucks/create', 'create')->name('trucks.create');
    Route::post('/trucks', 'store')->name('trucks.store');
    Route::get('/trucks/{truck}', 'show')->name('trucks.show');
    Route::get('/trucks/{truck}/edit', 'edit')->name('trucks.edit');
    Route::put('/trucks/{truck}', 'update')->name('trucks.update');
    Route::delete('/trucks/{truck}', 'destroy')->name('trucks.destroy');
});

// Subunit routes Subunit controller
Route::controller(TruckSubunitController::class)->group(function () {
    Route::get('/trucks/{truck}/subunits/create', 'create')->name('trucks.subunits.create');
    Route::post('/trucks/{truck}/subunits', 'store')->name('trucks.subunits.store');
    Route::delete('/trucks/{truck}/subunits/{subunit}', 'destroy')->name('trucks.subunits.destroy');
});

Route::get('/activity-logs', [ActivityLogController::class, 'show'])->name('activity-logs.show');