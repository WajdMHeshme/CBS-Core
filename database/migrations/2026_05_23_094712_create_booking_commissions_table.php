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
        Schema::create('booking_commissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();

            $table->foreignId('employee_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('lessor_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // 💡 مهم: نضيف حالة request أولية
            $table->enum('status', [
                'requested',        // جديد (الموظف طلب العمولة)
                'pending',          // بانتظار الدفع
                'payment_uploaded', // المؤجر رفع إثبات
                'paid',             // تم التأكيد
                'rejected',
            ])->default('requested');

            $table->decimal('amount', 10, 2)->default(0);

            $table->string('currency')->default('SYP');

            $table->string('payment_reference')->nullable();
            $table->string('payment_image')->nullable();

            $table->timestamp('requested_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_commissions');
    }
};
