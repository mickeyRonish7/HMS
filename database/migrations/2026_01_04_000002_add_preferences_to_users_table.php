<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('locale')->default('en')->after('profile_photo_path'); // en, ne
            $table->string('theme')->default('light')->after('locale'); // light, dark
            $table->string('font_size')->default('medium')->after('theme'); // small, medium, large
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['locale', 'theme', 'font_size']);
        });
    }
};
