<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student'); // admin, warden, student
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('bed_id')->nullable()->constrained('beds')->nullOnDelete();
            // Assign allocations directly to user for simplicity, or use a separate mapping if history needed
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['bed_id']);
            $table->dropColumn(['role', 'phone', 'address', 'bed_id']);
        });
    }
};
