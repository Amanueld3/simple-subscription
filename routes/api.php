<?php

use App\Http\Controllers\WebsiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('websites', WebsiteController::class);
Route::get('all-websites', [WebsiteController::class, 'get_all']);
