<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. CRM & Lead Pipeline
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('contact_name');
            $table->string('company_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('stage', 30)->default('new'); // new, qualified, proposal, negotiation, won, lost
            $table->unsignedBigInteger('estimated_value_cents')->default(0);
            $table->string('currency', 3)->default('USD');
            $table->text('notes')->nullable();
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['organization_id', 'stage']);
        });

        // 2. Client & Engagement Management (Consultancy & Projects)
        Schema::create('project_engagements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('client_name');
            $table->string('engagement_type', 50)->default('consultancy'); // consultancy, audit_preparation, decarbonization_roadmap
            $table->string('status', 30)->default('in_progress'); // planned, in_progress, completed, on_hold
            $table->unsignedBigInteger('budget_amount_cents')->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'status']);
        });

        // 3. Project Timesheets & Billable Hours
        Schema::create('project_timesheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_engagement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('work_date');
            $table->decimal('hours_spent', 5, 2);
            $table->unsignedBigInteger('hourly_rate_cents')->default(0);
            $table->text('task_description');
            $table->boolean('is_billable')->default(true);
            $table->timestamps();

            $table->index(['project_engagement_id', 'work_date']);
        });

        // 4. Invoicing & Billing
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->string('client_name');
            $table->string('billing_email');
            $table->unsignedBigInteger('subtotal_cents')->default(0);
            $table->unsignedBigInteger('tax_amount_cents')->default(0);
            $table->unsignedBigInteger('total_amount_cents')->default(0);
            $table->string('currency', 3)->default('USD');
            $table->string('status', 30)->default('draft'); // draft, sent, paid, overdue, void
            $table->date('due_date');
            $table->timestamp('paid_at')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'status']);
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->integer('quantity')->default(1);
            $table->unsignedBigInteger('unit_price_cents')->default(0);
            $table->unsignedBigInteger('line_total_cents')->default(0);
            $table->timestamps();
        });

        // 5. Accounting Lite (Double Entry Ledger & Green Finance Tracking)
        Schema::create('general_ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('account_code', 30);
            $table->string('account_name');
            $table->date('entry_date');
            $table->unsignedBigInteger('debit_cents')->default(0);
            $table->unsignedBigInteger('credit_cents')->default(0);
            $table->boolean('is_green_capex')->default(false); // Green Finance / Taxonomy
            $table->boolean('is_green_opex')->default(false);
            $table->string('reference')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'account_code', 'entry_date']);
        });

        // 6. Help Desk & Ticketing
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('ticket_number', 30)->unique();
            $table->string('requester_name');
            $table->string('requester_email');
            $table->string('subject');
            $table->string('priority', 20)->default('medium'); // low, medium, high, urgent
            $table->string('status', 30)->default('open'); // open, in_progress, resolved, closed
            $table->string('category', 50)->default('general'); // esg_data, billing, api, compliance
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['organization_id', 'status']);
        });

        Schema::create('support_ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_staff')->default(false);
            $table->text('message');
            $table->timestamps();
        });

        // 7. Training Academy & Certificates
        Schema::create('academy_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('level', 30)->default('beginner'); // beginner, intermediate, advanced
            $table->integer('duration_hours')->default(2);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('sustainability_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academy_course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('certificate_number')->unique();
            $table->string('recipient_name');
            $table->date('completion_date');
            $table->string('certificate_pdf_path')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'certificate_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sustainability_certificates');
        Schema::dropIfExists('academy_courses');
        Schema::dropIfExists('support_ticket_messages');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('general_ledger_entries');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('project_timesheets');
        Schema::dropIfExists('project_engagements');
        Schema::dropIfExists('crm_leads');
    }
};
