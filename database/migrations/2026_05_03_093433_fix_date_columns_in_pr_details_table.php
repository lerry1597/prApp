<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Gunakan raw statement untuk PostgreSQL agar bisa melakukan cast secara paksa
        DB::statement('ALTER TABLE pr_details ALTER COLUMN request_date_client TYPE timestamp(0) without time zone USING NULL');
        DB::statement('ALTER TABLE pr_details ALTER COLUMN issue_date TYPE timestamp(0) without time zone USING NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pr_details', function (Blueprint $table) {
            $table->string('request_date_client')->nullable()->change();
            $table->string('issue_date')->nullable()->change();
        });
    }
};
