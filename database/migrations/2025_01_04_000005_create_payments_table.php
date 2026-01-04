<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id')->nullable();
            $table->date('date');
            $table->string('method')->default('cash'); // cash, online
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
