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
            $table->string('status')->nullable()->after('item_priority');
        });

        Schema::table('items_log', function (Blueprint $table) {
            $table->string('status')->nullable()->after('item_priority');
        });

        Schema::table('items_history', function (Blueprint $table) {
            $table->string('status')->nullable()->after('item_priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('items_log', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('items_history', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
