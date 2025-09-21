<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Employee extends Model
{
    use HasFactory;

    /**
     * 一括割り当て可能な属性
     */
    protected $fillable = [
        'employee_number',
        'name',
        'name_kana',
        'email',
        'phone',
        'hire_date',
        'resignation_date',
        'is_active',
        'department',
        'position',
        'salary',
        'employment_type',
        'birth_date',
        'notes',
    ];

    /**
     * キャストする属性
     */
    protected $casts = [
        'hire_date' => 'date',
        'resignation_date' => 'date',
        'birth_date' => 'date',
        'is_active' => 'boolean',
        'salary' => 'decimal:0',
    ];

    /**
     * 在籍中の従業員のみ取得するスコープ
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * 部署でフィルタするスコープ
     */
    public function scopeDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * 年齢を計算するアクセサ
     */
    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }
        return $this->birth_date->diffInYears(Carbon::now());
    }

    /**
     * 勤続年数を計算するアクセサ
     */
    public function getYearsOfServiceAttribute()
    {
        $endDate = $this->resignation_date ?: Carbon::now();
        return $this->hire_date->diffInYears($endDate);
    }

    /**
     * 表示用の在籍状況
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active ? '在籍' : '退職';
    }

    /**
     * フルネーム（カナ付き）
     */
    public function getFullNameAttribute()
    {
        if ($this->name_kana) {
            return "{$this->name} ({$this->name_kana})";
        }
        return $this->name;
    }
}
