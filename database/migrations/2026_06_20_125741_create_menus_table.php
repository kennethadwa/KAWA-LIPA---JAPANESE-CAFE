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
    Schema::create('menus', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        // 8 total digits, 2 digits after the decimal point (e.g., 999,999.99)
        $table->decimal('price', 8, 2); 
        $table->string('category'); // e.g., 'coffee', 'pastry', 'meals'
        $table->boolean('is_available')->default(true);
        $table->string('image_path')->nullable(); // For file storage tracking
        $table->timestamps(); // Automatically handles created_at & updated_at
    });
}
public function down(): void { Schema::dropIfExists('menus'); }
};
