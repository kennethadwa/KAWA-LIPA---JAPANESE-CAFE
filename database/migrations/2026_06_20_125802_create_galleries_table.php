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
    Schema::create('galleries', function (Blueprint $table) {
        $table->id();
        $table->string('title')->nullable(); // Optional caption line
        $table->string('image_path'); // Physical photo reference path
        $table->string('type')->default('event'); // 'customer', 'crew', 'event'
        $table->boolean('featured')->default(false); // Highlight item switch
        $table->timestamps();
    });
}
public function down(): void { Schema::dropIfExists('galleries'); }
};
