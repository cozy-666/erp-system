<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * 従業員一覧取得
     */
    public function index(Request $request): JsonResponse
    {
        $query = Employee::query();

        // 検索機能
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 部署でフィルタ
        if ($request->has('department') && $request->department !== '') {
            $query->where('department', $request->department);
        }

        // 在籍状況でフィルタ
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // ソート
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // ページネーション
        $perPage = $request->get('per_page', 20);
        $employees = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => '従業員一覧を取得しました',
            'data' => $employees,
        ]);
    }

    /**
     * 従業員詳細取得
     */
    public function show(Employee $employee): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => '従業員詳細を取得しました',
            'data' => $employee,
        ]);
    }

    /**
     * 従業員新規作成
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_number' => 'required|string|unique:employees,employee_number',
            'name' => 'required|string|max:255',
            'name_kana' => 'nullable|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'nullable|string|max:20',
            'hire_date' => 'required|date',
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'salary' => 'nullable|numeric|min:0',
            'employment_type' => 'required|string|max:50',
            'birth_date' => 'nullable|date|before:today',
            'notes' => 'nullable|string',
        ]);

        $employee = Employee::create($validated);

        return response()->json([
            'success' => true,
            'message' => '従業員を登録しました',
            'data' => $employee,
        ], 201);
    }

    /**
     * 従業員情報更新
     */
    public function update(Request $request, Employee $employee): JsonResponse
    {
        $validated = $request->validate([
            'employee_number' => [
                'required',
                'string',
                Rule::unique('employees')->ignore($employee->id),
            ],
            'name' => 'required|string|max:255',
            'name_kana' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('employees')->ignore($employee->id),
            ],
            'phone' => 'nullable|string|max:20',
            'hire_date' => 'required|date',
            'resignation_date' => 'nullable|date|after:hire_date',
            'is_active' => 'boolean',
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'salary' => 'nullable|numeric|min:0',
            'employment_type' => 'required|string|max:50',
            'birth_date' => 'nullable|date|before:today',
            'notes' => 'nullable|string',
        ]);

        $employee->update($validated);

        return response()->json([
            'success' => true,
            'message' => '従業員情報を更新しました',
            'data' => $employee,
        ]);
    }

    /**
     * 従業員削除
     */
    public function destroy(Employee $employee): JsonResponse
    {
        // 物理削除ではなく、is_activeをfalseにする（論理削除）
        $employee->update([
            'is_active' => false,
            'resignation_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => '従業員を退職処理しました',
        ]);
    }

    /**
     * 統計情報取得
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::active()->count(),
            'departments' => Employee::active()
                ->select('department')
                ->groupBy('department')
                ->get()
                ->pluck('department'),
            'recent_hires' => Employee::active()
                ->where('hire_date', '>=', now()->subMonths(3))
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'message' => '統計情報を取得しました',
            'data' => $stats,
        ]);
    }
}
