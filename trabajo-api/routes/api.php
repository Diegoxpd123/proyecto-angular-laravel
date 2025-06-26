<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\UsuarioTenantController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PlanVentaController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StyleController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });
});

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('branches', BranchController::class);
});
Route::apiResource('styles', StyleController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('planventas', PlanVentaController::class);
Route::apiResource('product-details', ProductDetailController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('genres', GenreController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('clientes', ClienteController::class);
Route::apiResource('books', BookController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('details', DetailController::class);
Route::apiResource('usuarios', UsuarioController::class);
Route::apiResource('tenants', TenantController::class);
Route::apiResource('tenantsuser', UsuarioTenantController::class);
Route::get('/tenants', [TenantController::class, 'getTenants']);
Route::post('/tenants/set', [TenantController::class, 'setTenant']);
Route::get('/tenants/current', [TenantController::class, 'getCurrentTenant']);
