<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FyresController;
use App\Http\Controllers\AutomatedController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\ScalpingController;
use Illuminate\Support\Facades\Route;

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
        return view('welcome');
    });
    Route::post('/cookie-hook', [LogController::class, 'add']);
    Route::get('/test', [LogController::class, 'test']);
    //options
    Route::get('/autoPE-placement', [OptionController::class, 'autoPEPlacement']);
    Route::get('/autoCE-placement', [OptionController::class, 'autoCEPlacement']);
    
    Route::any('/order-placed', [OrderController::class, 'orderPlacement']);
;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    
    Route::get('/login_success', [FyresController::class, 'loginSuccess'])->name('fyreslogin.success');
    Route::get('/fyers-home', [FyresController::class, 'summary'])->name('fyreslogin.summary');
    Route::get('/fyers-positions', [FyresController::class, 'positions'])->name('fyreslogin.positions');
    Route::get('/dashboard', [FyresController::class, 'dashboard'])->name('dashboard.index');
    
    
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/automated', [AutomatedController::class, 'index'])->name('automated.index');
    
    
    Route::get('/log-reports', [LogController::class, 'logReports'])->name('order.logReports');
    Route::get('/logs/{stock?}', [LogController::class, 'index']);
    
    
    Route::get('/updateStatus/{id}', [DataController::class, 'updateStatus']);
    Route::get('/watchlist', [DataController::class, 'watchlist']);
    Route::get('/top-movers', [DataController::class, 'getTopMovingStocks']);
    Route::get('/volume-movers', [DataController::class, 'getVolumeMovingStocks']);
    Route::get('/stocks', [DataController::class, 'listStocks']);
    Route::get('/stock-buy/{stock?}/{bid?}', [DataController::class, 'stockBuy']);
    Route::get('/stock-sell/{stock?}/{ask?}', [DataController::class, 'stockSell']);
    
    Route::get('/option-logs/{day?}', [OptionController::class, 'index']);
    
    Route::get('/option-scalper', [ScalpingController::class, 'index']);
    Route::get('/nifty-bank-scalpe/{type}/{rate}', [ScalpingController::class, 'scalpingPlacement']);
    

});

require __DIR__.'/auth.php';
