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
    Schema::create('habit_journals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->date('date');
        $table->text('content');
        $table->integer('relapse_score')->default(0);
        $table->string('risk_level')->default('low'); // low, medium, high
        $table->timestamps();

        $table->unique(['user_id', 'date']); // satu jurnal per hari
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_journals');
    }
};
