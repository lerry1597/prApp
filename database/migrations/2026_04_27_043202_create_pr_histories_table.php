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
        Schema::create('pr_histories', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id')->index(); // ID unik untuk mengelompokkan 1 rangkaian flow (cycle)
            
            // Data dari pr_headers (Tanpa Constraint)
            $table->unsignedBigInteger('pr_header_id'); // Referensi: pr_headers.id
            $table->string('pr_number')->nullable();
            $table->string('pr_status')->nullable();
            $table->unsignedBigInteger('requester_id')->nullable(); // Referensi: users.id
            $table->unsignedBigInteger('department_id')->nullable(); // Referensi: departments.id
            $table->unsignedBigInteger('approver_id')->nullable(); // Referensi: users.id
            $table->unsignedBigInteger('approval_workflow_id')->nullable(); // Referensi: approval_workflows.id
            $table->unsignedBigInteger('current_role_id')->nullable(); // Referensi: roles.id
            $table->unsignedBigInteger('current_step_id')->nullable(); // Referensi: approval_steps.id
            $table->text('header_description')->nullable();

            // Data dari pr_details (Flat)
            $table->string('priority')->nullable();
            $table->string('document_no')->nullable();
            $table->string('title')->nullable();
            $table->string('issue_date')->nullable();
            $table->string('rev_no')->nullable();
            $table->date('ref_date')->nullable();
            $table->string('document_type')->nullable();
            $table->string('no')->nullable();
            $table->string('needs')->nullable();
            $table->unsignedBigInteger('vessel_id')->nullable(); // Referensi: vessels.id
            $table->date('request_date')->nullable();
            $table->date('required_date')->nullable();
            $table->date('expired_date')->nullable();
            $table->text('detail_description')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index('pr_header_id');
            $table->index('pr_number');
            $table->index('pr_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr_histories');
    }
};
