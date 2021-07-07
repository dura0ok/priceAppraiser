<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'catalogs','as'=>'catalogs.'], function(){
    Route::post("store", [CatalogController::class, "store"])->name("store");
    Route::put("update", [CatalogController::class, "update"])->name("update");
    Route::delete("destroy", [CatalogController::class, "destroy"])->name("destroy");
});

Route::group(["prefix" => "products", "as" => "products."], function(){
    Route::get("get", [ProductController::class, "getProducts"])->name("getProducts");
    Route::put("update/{id}", [ProductController::class, "update"])->name("update");
    Route::delete("destroy/{id}", [ProductController::class, "destroy"])->name("destroy");
});

