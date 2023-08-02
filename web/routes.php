<?php

use Core\Route;
use App\Controllers\Basket\BasketItemsController;
use App\Controllers\Basket\BasketAddItemController;
use App\Controllers\Basket\BasketRemoveItemController;
use App\Controllers\Campaign\CampaignListController;
use App\Controllers\Core\ErrorCodeController;
use App\Controllers\TestController;

#TODO: route sistemine middleware eklenebilir ve auth kontrolü yapılabilir

Route::get('/error-code', [ErrorCodeController::class, 'action']);

Route::match(['get', 'post'], '/test', [TestController::class, 'index']);

// # get basket
Route::get('/basket/items', [BasketItemsController::class, 'items']);
// # add to basket
Route::post('/basket/add', [BasketAddItemController::class, 'add']);
// # remove from basket
Route::post('/basket/remove', [BasketRemoveItemController::class, 'remove']);
// # clear basket
Route::post('/basket/clear', [BasketItemsController::class, 'clear']);

// # kampanyalar
Route::get('/campaigns', [CampaignListController::class, 'list']);
