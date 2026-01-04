<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('student_id_number')->nullable()->unique()->after('email');
            $table->date('id_card_issued_date')->nullable()->after('font_size');
            $table->date('id_card_expiry_date')->nullable()->after('id_card_issued_date');
            $table->string('blood_group')->nullable()->after('id_card_expiry_date');
            $table->string('emergency_contact')->nullable()->after('blood_group');
            $table->string('emergency_contact_name')->nullable()->after('emergency_contact');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'student_id_number',
                'id_card_issued_date',
                'id_card_expiry_date',
                'blood_group',
                'emergency_contact',
                'emergency_contact_name'
            ]);
        });
    }
};
