<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['info', 'success', 'warning', 'danger'])->default('info');
            $table->string('target_role')->nullable(); // null = all roles
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // specific user
            $table->boolean('is_read')->default(false);
            $table->boolean('send_email')->default(false);
            $table->boolean('email_sent')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('notifications'); }
};
