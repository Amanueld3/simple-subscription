<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('websites', WebsiteController::class);
Route::get('all-websites', [WebsiteController::class, 'get_all']);

Route::post('subscribe', [SubscriptionController::class, 'store']);

Route::resource('posts', PostController::class);
Route::get('all-posts', [PostController::class, 'get_all']);
