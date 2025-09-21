<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeController;

Route::get('/', function () {
    return view('welcome');
});

// APIルートを追加（一時的）
Route::prefix('api')->group(function () {
    Route::get('/health', function () {
        return response()->json([
            'success' => true,
            'message' => 'API is running',
            'timestamp' => now(),
        ]);
    });

    Route::get('/test', function () {
        return response()->json([
            'success' => true,
            'message' => 'Test API working!',
            'data' => [
                'version' => '1.0.0',
                'environment' => app()->environment(),
            ]
        ]);
    });

    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeController::class, 'index']);
        Route::get('/stats', [EmployeeController::class, 'stats']);
        Route::get('/{employee}', [EmployeeController::class, 'show']);
        Route::post('/', [EmployeeController::class, 'store']);
        Route::put('/{employee}', [EmployeeController::class, 'update']);
        Route::delete('/{employee}', [EmployeeController::class, 'destroy']);
    });
});
