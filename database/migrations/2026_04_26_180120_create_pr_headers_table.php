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
        Schema::create('pr_headers', function (Blueprint $table) {
            $table->id();
            $table->string('pr_number')->unique();
            $table->string('pr_status')->default('draft'); // draft, pending, approved, rejected, cancelled 
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->foreignId('approver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approval_workflow_id')->nullable()->constrained('approval_workflows')->nullOnDelete();
            //untuk next approval_role
            $table->foreignId('current_role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->foreignId('current_step_id')->nullable()->constrained('approval_steps')->nullOnDelete();
            $table->text('description')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['pr_number']);
            $table->index('pr_status');
            $table->index('requester_id');
            $table->index('department_id');
            $table->index('approval_workflow_id');
            $table->index('current_role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr_headers');
    }
};
