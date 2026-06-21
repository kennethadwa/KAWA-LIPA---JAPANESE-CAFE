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
    Schema::create('calendar_notes', function (Blueprint $table) {
        $id = $table->id();
        $table->date('note_date')->unique(); // The key (e.g., 2026-06-21)
        $table->string('title')->nullable();
        $table->text('body')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_notes');
    }
};
