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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pr_detail_id')->constrained('pr_details')->cascadeOnDelete();
            $table->foreignId('vessel_id')->nullable()->constrained('vessels')->nullOnDelete();
            $table->bigInteger('no');
            $table->string('type');
            $table->string('size'); //ukuran
            $table->text('description');
            $table->decimal('quantity', 15, 2);
            $table->string('unit'); // e.g. "pcs", "kg"
            $table->timestamps();
            $table->softDeletes();

            $table->index('vessel_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
