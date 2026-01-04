<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('year')->nullable()->after('parent_phone'); // 1-4
            $table->string('department')->nullable()->after('year');
            $table->integer('semester')->nullable()->after('department'); // 1-8
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['year', 'department', 'semester']);
        });
    }
};
