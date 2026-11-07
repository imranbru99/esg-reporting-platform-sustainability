<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ==========================================
        // C. MATERIALITY & STRATEGY
        // ==========================================

        // 1. Materiality Topics Library
        Schema::create('materiality_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('category', 20)->default('environmental'); // environmental, social, governance
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'category']);
        });

        // 2. Double Materiality Assessments (Impact & Financial Matrix)
        Schema::create('materiality_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('materiality_topic_id')->constrained()->cascadeOnDelete();
            $table->string('reporting_year', 4);
            $table->decimal('impact_materiality_score', 3, 2); // 1.00 to 5.00 (Inside-out impact)
            $table->decimal('financial_materiality_score', 3, 2); // 1.00 to 5.00 (Outside-in financial)
            $table->boolean('is_double_material')->default(false);
            $table->string('stakeholder_priority', 20)->default('medium'); // low, medium, high
            $table->string('status', 20)->default('approved');
            $table->timestamps();

            $table->index(['organization_id', 'reporting_year']);
        });

        // 3. Stakeholder Engagement Register
        Schema::create('stakeholders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('stakeholder_group', 50); // investors, employees, regulators, local_community, customers, suppliers
            $table->string('influence_level', 20)->default('high'); // high, medium, low
            $table->string('interest_level', 20)->default('high');
            $table->string('engagement_method', 50)->default('annual_survey'); // survey, bilateral_meeting, advisory_panel
            $table->text('key_concerns')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'stakeholder_group']);
        });

        // 4. ESG Strategy, SMART Targets & SBTi Roadmaps
        Schema::create('esg_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('target_type', 50); // sbti_1_5c, net_zero_2050, renewable_100, water_neutrality, zero_waste
            $table->string('baseline_year', 4);
            $table->decimal('baseline_value', 18, 4);
            $table->string('target_year', 4);
            $table->decimal('target_value', 18, 4);
            $table->decimal('current_value', 18, 4);
            $table->string('unit', 30);
            $table->decimal('progress_percentage', 5, 2)->default(0.00);
            $table->string('sdg_link', 20)->nullable(); // e.g. SDG 13
            $table->string('status', 20)->default('on_track'); // on_track, at_risk, behind, achieved
            $table->foreignId('owner_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['organization_id', 'status']);
        });

        // 5. Climate Risks & Opportunities (TCFD Scenarios)
        Schema::create('climate_risks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('risk_category', 30); // physical_acute, physical_chronic, transition_policy, transition_market, transition_tech
            $table->string('climate_scenario', 20)->default('1.5C'); // 1.5C, 2.0C, 4.0C
            $table->string('time_horizon', 20)->default('medium'); // short (0-3y), medium (3-10y), long (10-30y)
            $table->string('likelihood', 20)->default('medium');
            $table->unsignedBigInteger('estimated_financial_impact_cents')->default(0);
            $table->text('mitigation_plan')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'risk_category']);
        });

        // ==========================================
        // G. SUPPLY CHAIN & SCOPE 3
        // ==========================================

        // 6. Supplier Directory & Risk Scoring
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('tier', 20)->default('tier_1'); // tier_1, tier_2, tier_3_raw
            $table->string('country_code', 3)->default('BGD');
            $table->string('contact_email')->nullable();
            $table->string('esg_risk_rating', 20)->default('low'); // low, medium, high, critical
            $table->boolean('portal_access_enabled')->default(true);
            $table->boolean('is_onboarded')->default(true);
            $table->timestamps();

            $table->index(['organization_id', 'tier', 'esg_risk_rating']);
        });

        // 7. Supplier ESG Assessments & Scorecards
        Schema::create('supplier_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('questionnaire_name');
            $table->decimal('score_percentage', 5, 2)->default(0);
            $table->string('status', 30)->default('approved'); // draft, sent, submitted, reviewed, approved, corrective_action_required
            $table->text('corrective_action_plan')->nullable();
            $table->date('reassessment_due_date')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'status']);
        });

        // 8. Scope 3 Supplier Primary Ingestion
        Schema::create('supplier_scope3_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('reporting_year', 4);
            $table->string('category', 50)->default('purchased_goods'); // purchased_goods, capital_goods, freight
            $table->unsignedBigInteger('spend_amount_cents')->default(0);
            $table->decimal('allocated_emissions_tco2e', 14, 4)->default(0);
            $table->string('calculation_method', 30)->default('spend_based'); // spend_based, supplier_specific_hybrid
            $table->string('status', 30)->default('verified'); // requested, received, verified
            $table->timestamps();

            $table->index(['organization_id', 'reporting_year']);
        });

        // 9. Batch Traceability Link (Garment/Food)
        Schema::create('traceability_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('batch_number')->unique();
            $table->string('product_name');
            $table->string('material_origin_country', 3)->default('BGD');
            $table->string('certification_type')->nullable(); // GOTS, BCI, FSC, OEKO-TEX
            $table->string('certification_id')->nullable();
            $table->boolean('eudr_deforestation_free')->default(true); // EUDR compliance
            $table->timestamps();

            $table->index(['organization_id', 'batch_number']);
        });

        // ==========================================
        // H. ASSURANCE WORKSPACE & AUDIT
        // ==========================================

        // 10. Assurance Engagements (External Auditor Data Room)
        Schema::create('assurance_engagements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('audit_firm'); // e.g. PwC, EY, KPMG, Deloitte, SGS, TÜV
            $table->string('standard', 30)->default('ISAE 3000'); // ISAE 3000, ISAE 3410 (GHG), AA1000AS
            $table->string('assurance_level', 30)->default('limited'); // limited, reasonable
            $table->string('reporting_period_covered');
            $table->string('status', 30)->default('signed_off'); // planning, fieldwork, draft_statement, signed_off
            $table->date('signed_off_date')->nullable();
            $table->string('statement_pdf_path')->nullable();
            $table->timestamp('dataroom_expires_at')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'status']);
        });

        // 11. Audit Workpapers & Findings
        Schema::create('audit_workpapers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assurance_engagement_id')->constrained()->cascadeOnDelete();
            $table->string('disclosure_code'); // e.g. GRI 305-1, ESRS E1-6
            $table->string('title');
            $table->text('audit_procedure_description');
            $table->string('finding_type', 30)->default('no_exception'); // no_exception, observation, qualification, limitation
            $table->text('auditor_notes')->nullable();
            $table->foreignId('auditor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['assurance_engagement_id', 'finding_type']);
        });

        // 12. Restatements & Change Log
        Schema::create('restatement_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('metric_id')->constrained()->cascadeOnDelete();
            $table->string('reporting_year', 4);
            $table->decimal('original_value', 18, 4);
            $table->decimal('restated_value', 18, 4);
            $table->string('unit', 30);
            $table->text('justification_reason');
            $table->text('impact_summary')->nullable();
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['organization_id', 'reporting_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restatement_logs');
        Schema::dropIfExists('audit_workpapers');
        Schema::dropIfExists('assurance_engagements');
        Schema::dropIfExists('traceability_batches');
        Schema::dropIfExists('supplier_scope3_requests');
        Schema::dropIfExists('supplier_assessments');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('climate_risks');
        Schema::dropIfExists('esg_targets');
        Schema::dropIfExists('stakeholders');
        Schema::dropIfExists('materiality_assessments');
        Schema::dropIfExists('materiality_topics');
    }
};
