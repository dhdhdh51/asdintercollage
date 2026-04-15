<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->string('application_id')->unique(); // AUTO-GENERATED e.g. APP2024001
            $table->string('student_name');
            $table->string('father_name');
            $table->string('mother_name')->nullable();
            $table->date('dob');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->text('address');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('previous_class')->nullable();
            $table->string('document_path')->nullable(); // Uploaded documents
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('remarks')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->year('academic_year');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('admissions'); }
};
