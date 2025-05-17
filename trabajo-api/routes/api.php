<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('clientes',ClienteController::class);
Route::apiResource('books',BookController::class);
Route::apiResource('orders',OrderController::class);
Route::apiResource('details',DetailController::class);

