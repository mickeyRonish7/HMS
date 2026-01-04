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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('student_approved')->default(false)->after('is_approved');
            $table->boolean('admin_approved')->default(false)->after('student_approved');
        });

        // Migrate existing approved visitors to new system
        DB::table('users')
            ->where('role', 'visitor')
            ->where('is_approved', true)
            ->update([
                'student_approved' => true,
                'admin_approved' => true,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['student_approved', 'admin_approved']);
        });
    }
};
