<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\productController;

Route::get('products', [productController::class, 'index']);

Route::get('products/{id}', [productController::class, 'show']);

// Route::post('products', [productController::class, 'store']);

// Route::put('products/{id}', [productController::class, 'update']);

Route::patch('products/{id}', [productController::class, 'updatePartial']);

Route::delete('products/{id}', [productController::class, 'delete']);

// Rutas Protegidas (Solo con Token JWT válido)
Route::middleware(['jwt.verify'])->group(function () {
    Route::post('/products', [ProductController::class, 'store']);       // Crear
    Route::put('/products/{id}', [ProductController::class, 'update']);  // Actualizar
//     Route::delete('/products/{id}', [ProductController::class, 'destroy']); // Borrar
});