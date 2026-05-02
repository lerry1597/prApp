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
        $tableNames = ['items_log', 'items_history'];

        foreach ($tableNames as $tableName) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('batch_id')->nullable()->index();
                $table->unsignedBigInteger('pr_detail_id')->nullable()->index();
                $table->unsignedBigInteger('vessel_id')->nullable()->index();
                $table->unsignedBigInteger('item_category_id')->nullable()->index();
                $table->bigInteger('no')->nullable();
                $table->string('type')->nullable();
                $table->string('size')->nullable();
                $table->text('description')->nullable();
                $table->decimal('quantity', 15, 2)->nullable();
                $table->string('unit')->nullable();
                $table->decimal('remaining', 15, 2)->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items_log');
        Schema::dropIfExists('items_history');
    }
};
