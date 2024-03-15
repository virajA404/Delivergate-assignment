<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//register route
Route::post("register", [ApiController::class, "saveRegister"]);

//login route
Route::post("login", [ApiController::class, "loginAction"]);

Route::group([
    'middleware' => ['auth:sanctum']
], function () {
    //logout route
    Route::get("/logout", [ApiController::class, "logout"]);

    //add items
    Route::post('/additems', [ApiController::class, 'addItems']);

    //edit items
    Route::post('/edititems/{id}', [ApiController::class, 'editItems']);

    //delete items
    Route::get('/deleteitems/{id}', [ApiController::class, 'deleteItems']);

    //add customers
    Route::post('/addcustomers', [ApiController::class, 'addCustomers']);

    //edit customer details
    Route::post('/editcustomer/{id}', [ApiController::class, 'editCustomer']);

    //delete customer details
    Route::get('/deletecustomer/{id}', [ApiController::class, 'deleteCustomer']);
});
