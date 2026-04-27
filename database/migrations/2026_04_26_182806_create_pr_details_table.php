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
        Schema::create('pr_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pr_header_id')->unique()->constrained('pr_headers')->cascadeOnDelete();
            $table->string('priority')->default('normal'); // normal, high, urgent
            $table->string('document_no');
            $table->string('title')->nullable();
            $table->string('issue_date')->nullable();
            $table->string('rev_no')->nullable();
            $table->date('ref_date')->nullable();
            $table->string('document_type')->nullable();
            $table->string('no')->nullable();
            $table->string('needs')->nullable(); //mesin atau dek
            $table->foreignId('vessel_id')->nullable()->constrained('vessels')->nullOnDelete();
            //buatkan tabel item relasi 1 - N (ini harus di tabel items)
            $table->date('request_date');
            $table->date('required_date')->nullable();
            $table->date('expired_date')->nullable();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('priority');
            $table->index('document_no');
            $table->index('vessel_id');
            $table->index('request_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr_details');
    }
};
