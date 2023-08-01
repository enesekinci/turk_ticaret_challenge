<?php

use App\Controllers\TestController;
use Core\Route;


Route::match(['get', 'post'], '/test', [TestController::class, 'index']);

# get basket
Route::get('/basket', [BasketController::class, 'index']);
# add to basket
Route::post('/basket/add', [BasketController::class, 'add']);
# remove from basket
Route::post('/basket/remove', [BasketController::class, 'remove']);
# stok kontrolü
Route::post('/basket/check-stock', [BasketController::class, 'checkStock']);
# kampanyalar
Route::get('/campaigns', [CampaignController::class, 'index']);
