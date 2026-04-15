<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('occupation')->nullable();
            $table->string('annual_income')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // Parent-Student relationship
        Schema::create('parent_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('parents')->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->enum('relation', ['father', 'mother', 'guardian']);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('parent_student');
        Schema::dropIfExists('parents');
    }
};
