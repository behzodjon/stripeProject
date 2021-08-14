<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/buy/{product}', [ProductController::class, 'buy'])->name('products.buy');
Route::get('/products/buy/fail', [ProductController::class, 'buyFail'])->name('products.fail');
//Route::post('stripe/webhook', 'CheckoutController@stripePaymentWebhook')->middleware('stripe.verify');
