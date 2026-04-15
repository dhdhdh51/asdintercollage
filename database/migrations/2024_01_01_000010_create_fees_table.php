<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Fee categories (Tuition, Transport, Library, etc.)
        Schema::create('fee_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Fee structures per class
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('fee_category_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('frequency', ['monthly', 'quarterly', 'yearly', 'one_time'])->default('monthly');
            $table->year('academic_year');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Student fee records
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('fee_category_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('fine', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->enum('status', ['pending', 'partial', 'paid', 'overdue'])->default('pending');
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->string('month')->nullable(); // e.g., April 2024
            $table->year('academic_year');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // Payment transactions
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique(); // PayU transaction ID
            $table->string('invoice_number');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('fee_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['payu', 'cash', 'bank_transfer', 'cheque'])->default('cash');
            $table->enum('status', ['success', 'failed', 'pending'])->default('pending');
            $table->string('payu_txn_id')->nullable();
            $table->string('payu_mihpayid')->nullable();
            $table->json('gateway_response')->nullable();
            $table->string('receipt_number')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('fees');
        Schema::dropIfExists('fee_structures');
        Schema::dropIfExists('fee_categories');
    }
};
