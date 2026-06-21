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
        Schema::table('menus', function (Blueprint $table) {
            // 1. Drop the old string-based category column
            $table->dropColumn('category');

            // 2. Add the new unsigned big integer foreign key column
            // We use nullable() here as a safety measure if you already have existing menu items
            $table->foreignId('category_id')->nullable()->after('price')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // Rollback actions: remove foreign key and add back the old string column
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            
            $table->string('category')->after('price');
        });
    }
};