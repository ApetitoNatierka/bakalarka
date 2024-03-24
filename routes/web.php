<?php

use App\Http\Controllers\Address\AddressLineController;
use App\Http\Controllers\Orders\CartController;
use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\Orders\OrderLineController;
use App\Http\Controllers\Orders\ProductController;
use App\Http\Controllers\UserComp\CompanyController;
use App\Http\Controllers\UserComp\UserController;
use Illuminate\Support\Facades\Route;

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
    return view('start_page');
});

Route::get('/homepage', function () {
    return view('start_page');
});

Route::get('/intranet', function () {
    return view('intranet');
});


//---------------------------------------------------------------
//-----------------------User------------------------------------
//---------------------------------------------------------------

Route::get('/register', [UserController::class, 'get_register']);

Route::post('/register', [UserController::class, 'register']);

Route::get('/logout', [UserController::class, 'logout']);

Route::post('/sign_in', [UserController::class, 'sign_in']);

Route::get('/sign_in',  [UserController::class, 'get_sign_in']);

Route::get('/user_profile', [UserController::class, 'get_user_profile']);

Route::get('/select_users', [UserController::class, 'select_users']);

Route::post('/modify_user_info', [UserController::class, 'modify_user_info']);

Route::post('/modify_user_line', [UserController::class, 'modify_user_line']);

Route::post('/delete_user_line', [UserController::class, 'delete_user_line']);

Route::post('/add_new_user_line', [UserController::class, 'add_new_user_line']);

//---------------------------------------------------------------
//-----------------------Address---------------------------------
//---------------------------------------------------------------

Route::post('/add_new_address_line', [AddressLineController::class, 'add_new_address_line']);

Route::post('/modify_address_line', [AddressLineController::class, 'modify_address_line']);

Route::post('/delete_address_line', [AddressLineController::class, 'delete_address_line']);

//---------------------------------------------------------------
//-----------------------Company---------------------------------
//---------------------------------------------------------------

Route::get('/company_profile', [CompanyController::class, 'get_company_profile']);

Route::post('/add_company_info', [CompanyController::class, 'add_company_info']);

Route::post('/modify_company_info', [CompanyController::class, 'modify_company_info']);

//---------------------------------------------------------------
//-----------------------Product---------------------------------
//---------------------------------------------------------------

Route::get('/products', [ProductController::class, 'get_products']);

Route::get('/services', [ProductController::class, 'get_services']);

Route::get('/animals', [ProductController::class, 'get_animals']);

Route::get('/search_products', [ProductController::class, 'get_search_products']);

Route::get('/select_products', [ProductController::class, 'select_products']);

Route::post('/add_new_product', [ProductController::class, 'add_new_product']);

Route::post('/delete_product', [ProductController::class, 'delete_product']);

Route::post('/modify_product', [ProductController::class, 'modify_product']);

//---------------------------------------------------------------
//-----------------------Cart------------------------------------
//---------------------------------------------------------------

Route::get('/cart', [CartController::class, 'get_cart']);

Route::post('/add_to_cart', [CartController::class, 'add_to_cart']);

Route::post('/delete_cart_item', [CartController::class, 'delete_cart_item']);

Route::post('/modify_cart_item', [CartController::class, 'modify_cart_item']);

//---------------------------------------------------------------
//-----------------------Order-----------------------------------
//---------------------------------------------------------------

Route::get('/orders', [OrderController::class, 'get_orders'])->middleware('auth.redirect');

Route::get('/order', [OrderController::class, 'get_order_empty']);

Route::get('/order/{order}', [OrderController::class, 'get_order'])->middleware('auth.redirect');

Route::get('/search_orders', [OrderController::class, 'get_search_orders']);

Route::get('/download_order/{order}', [OrderController::class, 'download_order']);

Route::post('/create_order', [OrderController::class, 'create_order']);

Route::post('/add_order', [OrderController::class, 'add_order']);

Route::post('/delete_order', [OrderController::class, 'delete_order']);

Route::post('/modify_order', [OrderController::class, 'modify_order']);


//---------------------------------------------------------------
//-----------------------Order_Line------------------------------
//---------------------------------------------------------------

Route::post('/add_new_order_line', [OrderLineController::class, 'add_new_order_line']);

Route::post('/modify_order_line', [OrderLineController::class, 'modify_order_line']);

Route::post('/delete_order_line', [OrderLineController::class, 'delete_order_line']);

