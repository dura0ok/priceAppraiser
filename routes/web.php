<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get("login", [UserController::class, "getLoginPage"])->name("login");
Route::get("register", [UserController::class, "getRegisterPage"])->name("getRegisterPage");
Route::post("register", [UserController::class, "register"])->name("register");
Route::post("login", [UserController::class, "login"])->name("auth");
Route::get("logout", [UserController::class, "logout"])->name("logout");

Route::group(['middleware' => 'auth'], function () {
    Route::get("/", [PageController::class, "index"])->name("index");
    Route::group(['middleware' => ['admin']], function () {
        Route::get("users", [UserController::class, "users"])->name("users.index");
        Route::get("users/create", [UserController::class, "create"])->name("users.create");
        Route::post("users/store", [UserController::class, "store"])->name("users.store");
        Route::get("users/edit/{id}", [UserController::class, "edit"])->name("users.edit");
        Route::put("users/update/{id}", [UserController::class, "update"])->name("users.update");
        Route::delete("users/delete/{id}", [UserController::class, "destroy"])->name("users.destroy");
    });
    Route::group(['prefix'=>'catalogs','as'=>'catalogs.'], function(){
        Route::get("/", [CatalogController::class, "show"])->name("show");
        Route::get("choosePrint", [CatalogController::class, "getPrintPage"])->name("choosePrint");
        Route::post("print", [CatalogController::class, "print"])->name("print");
    });
    Route::group(['prefix'=>'products','as'=>'products.'], function(){
        Route::get("{catalog_id?}", [ProductController::class, "index"])->where('catalog_id', '[0-9]+')->name("index");
        Route::get("create", [ProductController::class, "create"])->name("create");
        Route::post("store", [ProductController::class, "store"])->name("store");
        Route::get("edit/{id}", [ProductController::class, "edit"])->name("edit");
        Route::get("import", [ProductController::class, "getImportPage"])->name("importPage");
        Route::post("import", [ProductController::class, "import"])->name("import");
        Route::get("search", [ProductController::class, "search"])->name("search");
        Route::post("search", [ProductController::class, "find"])->name("find");
        Route::get("found", [ProductController::class, "found"])->name("found");
        Route::get("products/appraise", [ProductController::class, "getAppraisePage"])->name("appraisePage");
        Route::post("products/appraise", [ProductController::class, "appraise"])->name("appraise");
        Route::get("showAppraisings", [ProductController::class, "showAppraisings"])->name("showAppraisings");
        Route::delete("deleteAppraising/{id}", [ProductController::class, "deleteAppraising"])->name("deleteAppraising");
    });
});
