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
        Schema::table('items_log', function (Blueprint $table) {
            $table->unsignedInteger('step_order')->nullable()->after('remaining');
        });

        Schema::table('items_history', function (Blueprint $table) {
            $table->unsignedInteger('step_order')->nullable()->after('remaining');
        });

        Schema::table('pr_histories', function (Blueprint $table) {
            $table->json('payload')->nullable()->after('detail_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items_log', function (Blueprint $table) {
            $table->dropColumn('step_order');
        });

        Schema::table('items_history', function (Blueprint $table) {
            $table->dropColumn('step_order');
        });

        Schema::table('pr_histories', function (Blueprint $table) {
            $table->dropColumn('payload');
        });
    }
};
