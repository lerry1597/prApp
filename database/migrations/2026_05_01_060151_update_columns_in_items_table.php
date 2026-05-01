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
        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('vessel_id')->nullable(false)->change();
            $table->bigInteger('no')->nullable()->change();
            $table->foreignId('item_category_id')->nullable(false)->change();
            $table->decimal('remaining', 15, 2)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('vessel_id')->nullable()->change();
            $table->bigInteger('no')->nullable(false)->change();
            $table->foreignId('item_category_id')->nullable()->change();
            $table->decimal('remaining', 15, 2)->nullable()->change();
        });
    }
};
