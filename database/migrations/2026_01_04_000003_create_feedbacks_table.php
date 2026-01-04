<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('room_rating')->nullable(); // 1-5
            $table->integer('mess_rating')->nullable(); // 1-5
            $table->integer('security_rating')->nullable(); // 1-5
            $table->integer('staff_rating')->nullable(); // 1-5
            $table->text('comments')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('status')->default('pending'); // pending, reviewed, resolved
            $table->text('admin_response')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
