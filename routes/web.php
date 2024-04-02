<?php

use App\Http\Controllers\Address\AddressLineController;
use App\Http\Controllers\Orders\CartController;
use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\Orders\OrderLineController;
use App\Http\Controllers\Orders\ProductController;
use App\Http\Controllers\Organisations\EmployeeController;
use App\Http\Controllers\Organisations\OrganisationController;
use App\Http\Controllers\UserComp\CompanyController;
use App\Http\Controllers\UserComp\UserController;
use App\Http\Controllers\Warehouses\AnimalController;
use App\Http\Controllers\Warehouses\AnimalNumberController;
use App\Http\Controllers\Warehouses\ItemController;
use App\Http\Controllers\Warehouses\SupplyController;
use App\Http\Controllers\Warehouses\SupplyNumberController;
use App\Http\Controllers\Warehouses\WarehouseController;
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

Route::middleware(['auth.redirect'])->group(function () {
    Route::get('/intranet', function () {
        return view('intranet');
    });
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
Route::middleware(['auth.redirect'])->group(function () {

    Route::post('/add_new_address_line', [AddressLineController::class, 'add_new_address_line']);

    Route::post('/modify_address_line', [AddressLineController::class, 'modify_address_line']);

    Route::post('/delete_address_line', [AddressLineController::class, 'delete_address_line']);
});

//---------------------------------------------------------------
//-----------------------Company---------------------------------
//---------------------------------------------------------------
Route::middleware(['auth.redirect'])->group(function () {

    Route::get('/company_profile', [CompanyController::class, 'get_company_profile']);

    Route::post('/add_company_info', [CompanyController::class, 'add_company_info']);

    Route::post('/modify_company_info', [CompanyController::class, 'modify_company_info']);

    Route::get('/customers', [CompanyController::class, 'get_customers']);

    Route::get('/suppliers', [CompanyController::class, 'get_suppliers']);

    Route::post('/add_company', [CompanyController::class, 'add_company']);

    Route::post('/delete_company', [CompanyController::class, 'delete_company']);

    Route::post('/modify_company', [CompanyController::class, 'modify_company']);

    Route::get('/search_companies', [CompanyController::class, 'get_search_companies']);

});

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

Route::middleware(['auth.redirect'])->group(function () {

    Route::get('/cart', [CartController::class, 'get_cart']);

    Route::post('/add_to_cart', [CartController::class, 'add_to_cart']);

    Route::post('/delete_cart_item', [CartController::class, 'delete_cart_item']);

    Route::post('/modify_cart_item', [CartController::class, 'modify_cart_item']);
});

//---------------------------------------------------------------
//-----------------------Order-----------------------------------
//---------------------------------------------------------------
Route::middleware(['auth.redirect'])->group(function () {
    Route::get('/orders', [OrderController::class, 'get_orders']);

    Route::get('/order', [OrderController::class, 'get_order_empty']);

    Route::get('/order/{order}', [OrderController::class, 'get_order']);

    Route::get('/search_orders', [OrderController::class, 'get_search_orders']);

    Route::get('/download_order/{order}', [OrderController::class, 'download_order']);

    Route::post('/create_order', [OrderController::class, 'create_order']);

    Route::post('/add_order', [OrderController::class, 'add_order']);

    Route::post('/delete_order', [OrderController::class, 'delete_order']);

    Route::post('/modify_order', [OrderController::class, 'modify_order']);
});

//---------------------------------------------------------------
//-----------------------Order_Line------------------------------
//---------------------------------------------------------------

Route::middleware(['auth.redirect'])->group(function () {

    Route::post('/add_new_order_line', [OrderLineController::class, 'add_new_order_line']);

    Route::post('/modify_order_line', [OrderLineController::class, 'modify_order_line']);

    Route::post('/delete_order_line', [OrderLineController::class, 'delete_order_line']);
});


//---------------------------------------------------------------
//-----------------------Oerganisation------------------------------
//---------------------------------------------------------------

Route::middleware(['auth.redirect'])->group(function () {

    Route::get('/organisations', [OrganisationController::class, 'get_organisations']);

    Route::get('/select_organisations', [OrganisationController::class, 'select_organisations']);

    Route::get('/search_organisations', [OrganisationController::class, 'get_search_organisations']);

    Route::post('/add_organisation', [OrganisationController::class, 'add_organisation']);

    Route::post('/delete_organisation', [OrganisationController::class, 'delete_organisation']);

    Route::post('/modify_organisation', [OrganisationController::class, 'modify_organisation']);

});

//---------------------------------------------------------------
//-----------------------Employee------------------------------
//---------------------------------------------------------------

Route::middleware(['auth.redirect'])->group(function () {

    Route::get('/employees', [EmployeeController::class, 'get_employees']);

    Route::post('/add_employee', [EmployeeController::class, 'add_employee']);

    Route::post('/delete_employee', [EmployeeController::class, 'delete_employee']);

    Route::post('/modify_employee', [EmployeeController::class, 'modify_employee']);

    Route::get('/search_employees', [EmployeeController::class, 'get_search_employees']);

});

//---------------------------------------------------------------
//-----------------------Warehouse-------------------------------
//---------------------------------------------------------------

Route::middleware(['auth.redirect'])->group(function () {

    Route::get('/warehouses', [WarehouseController::class, 'get_warehouses']);

    Route::post('/add_warehouse', [WarehouseController::class, 'add_warehouse']);

    Route::post('/delete_warehouse', [WarehouseController::class, 'delete_warehouse']);

    Route::post('/modify_warehouse', [WarehouseController::class, 'modify_warehouse']);

    Route::get('/search_warehouses', [WarehouseController::class, 'get_search_warehouses']);

});

//---------------------------------------------------------------
//-----------------------Animal No-------------------------------
//---------------------------------------------------------------

Route::middleware(['auth.redirect'])->group(function () {

    Route::get('/animal_numbers', [AnimalNumberController::class, 'get_animal_numbers']);

    Route::post('/add_animal_number', [AnimalNumberController::class, 'add_animal_number']);

    Route::post('/delete_animal_number', [AnimalNumberController::class, 'delete_animal_number']);

    Route::post('/modify_animal_number', [AnimalNumberController::class, 'modify_animal_number']);

    Route::get('/search_animal_numbers', [AnimalNumberController::class, 'get_search_animal_numbers']);

    Route::get('/select_animal_nos', [AnimalNumberController::class, 'select_animal_nos']);

});

//---------------------------------------------------------------
//-----------------------Supply No-------------------------------
//---------------------------------------------------------------

Route::middleware(['auth.redirect'])->group(function () {

    Route::get('/supply_numbers', [SupplyNumberController::class, 'get_supply_numbers']);

    Route::post('/add_supply_number', [SupplyNumberController::class, 'add_supply_number']);

    Route::post('/delete_supply_number', [SupplyNumberController::class, 'delete_supply_number']);

    Route::post('/modify_supply_number', [SupplyNumberController::class, 'modify_supply_number']);

    Route::get('/search_supply_numbers', [SupplyNumberController::class, 'get_search_supply_numbers']);

    Route::get('/select_supply_nos', [SupplyNumberController::class, 'select_supply_nos']);

});

//---------------------------------------------------------------
//-----------------------Animal----------------------------------
//---------------------------------------------------------------

Route::middleware(['auth.redirect'])->group(function () {

    Route::get('/animals', [AnimalController::class, 'get_animals']);

    Route::post('/add_animal', [AnimalController::class, 'add_animal']);

    Route::post('/delete_animal', [AnimalController::class, 'delete_animal']);

    Route::post('/modify_animal', [AnimalController::class, 'modify_animal']);

    Route::get('/search_animals', [AnimalController::class, 'get_search_animals']);

});

//---------------------------------------------------------------
//-----------------------Item------------------------------------
//---------------------------------------------------------------

Route::middleware(['auth.redirect'])->group(function () {

    Route::get('/items', [ItemController::class, 'get_items']);

    Route::post('/delete_item', [ItemController::class, 'delete_item']);

    Route::post('/modify_item', [ItemController::class, 'modify_item']);

    Route::get('/search_items', [ItemController::class, 'get_search_items']);

});

//---------------------------------------------------------------
//-----------------------Supply----------------------------------
//---------------------------------------------------------------

Route::middleware(['auth.redirect'])->group(function () {

    Route::get('/supplies', [SupplyController::class, 'get_supplies']);

    Route::post('/add_supply', [SupplyController::class, 'add_supply']);

    Route::post('/delete_supply', [SupplyController::class, 'delete_supply']);

    Route::post('/modify_supply', [SupplyController::class, 'modify_supply']);

    Route::get('/search_supplies', [SupplyController::class, 'get_search_supplies']);

});
