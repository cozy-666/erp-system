<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 従業員管理API
Route::prefix('employees')->group(function () {
    Route::get('/', [EmployeeController::class, 'index']);           // 一覧取得
    Route::post('/', [EmployeeController::class, 'store']);          // 新規作成
    Route::get('/stats', [EmployeeController::class, 'stats']);      // 統計情報
    Route::get('/{employee}', [EmployeeController::class, 'show']);  // 詳細取得
    Route::put('/{employee}', [EmployeeController::class, 'update']); // 更新
    Route::delete('/{employee}', [EmployeeController::class, 'destroy']); // 削除
});

// ヘルスチェック用
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is running',
        'timestamp' => now(),
    ]);
});
