<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // 基本情報
            $table->string('employee_number')->unique()->comment('社員番号');
            $table->string('name')->comment('氏名');
            $table->string('name_kana')->nullable()->comment('氏名（カナ）');
            $table->string('email')->unique()->comment('メールアドレス');
            $table->string('phone')->nullable()->comment('電話番号');

            // 入社・退社関連
            $table->date('hire_date')->comment('入社日');
            $table->date('resignation_date')->nullable()->comment('退社日');
            $table->boolean('is_active')->default(true)->comment('在籍状況');

            // 部署・役職
            $table->string('department')->comment('部署');
            $table->string('position')->comment('役職');

            // 給与関連
            $table->decimal('salary', 10, 0)->nullable()->comment('基本給');
            $table->string('employment_type')->default('正社員')->comment('雇用形態');

            // その他
            $table->date('birth_date')->nullable()->comment('生年月日');
            $table->text('notes')->nullable()->comment('備考');

            $table->timestamps();

            // インデックス
            $table->index(['department', 'is_active']);
            $table->index(['hire_date']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
