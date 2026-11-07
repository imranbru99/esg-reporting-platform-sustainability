<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ==========================================
        // D. ENVIRONMENTAL MODULES
        // ==========================================

        // 1. Utility Bills (AI OCR Ingestion)
        Schema::create('utility_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('utility_type', 30); // electricity, natural_gas, water, steam
            $table->string('provider_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('invoice_number')->nullable();
            $table->date('billing_period_start');
            $table->date('billing_period_end');
            $table->decimal('consumption_quantity', 18, 4);
            $table->string('consumption_unit', 30); // kWh, m3, therm, L
            $table->unsignedBigInteger('amount_cents')->default(0);
            $table->string('currency', 3)->default('USD');
            $table->string('file_path')->nullable();
            $table->string('ocr_status', 30)->default('pending'); // pending, processed, verified, failed
            $table->json('ai_extracted_data')->nullable();
            $table->foreignId('verified_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['organization_id', 'utility_type']);
        });

        // 2. Water Management
        Schema::create('water_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->string('withdrawal_source', 50); // municipal, groundwater, surface_water, rainwater
            $table->decimal('withdrawal_volume_m3', 18, 4);
            $table->string('discharge_destination', 50)->nullable(); // municipal_sewer, surface_water, offsite_treatment
            $table->decimal('discharge_volume_m3', 18, 4)->default(0);
            $table->decimal('recycled_volume_m3', 18, 4)->default(0);
            $table->string('water_stress_level', 20)->default('low'); // low, medium, high, extremely_high
            $table->decimal('effluent_bod_mg_l', 8, 2)->nullable(); // Biochemical Oxygen Demand
            $table->decimal('effluent_cod_mg_l', 8, 2)->nullable(); // Chemical Oxygen Demand
            $table->decimal('effluent_ph', 4, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'period_start']);
        });

        // 3. Waste & Circularity
        Schema::create('waste_manifests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('waste_stream'); // cardboard, plastic, fabric_scraps, e_waste, chemical_sludge
            $table->boolean('is_hazardous')->default(false);
            $table->decimal('weight_tonnes', 14, 4);
            $table->string('disposal_route', 50); // recycling, composting, energy_recovery, incineration, landfill
            $table->decimal('circular_recovery_pct', 5, 2)->default(0);
            $table->string('manifest_number')->nullable();
            $table->string('certified_hauler_name')->nullable();
            $table->string('certificate_file_url')->nullable();
            $table->date('disposal_date');
            $table->timestamps();

            $table->index(['organization_id', 'is_hazardous', 'disposal_route']);
        });

        // 4. Air Emissions & Effluents
        Schema::create('air_emissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('emission_source'); // boiler_1, generator_diesel, kiln, stack
            $table->date('monitoring_date');
            $table->decimal('nox_kg', 12, 4)->default(0);
            $table->decimal('sox_kg', 12, 4)->default(0);
            $table->decimal('particulate_matter_pm_kg', 12, 4)->default(0);
            $table->decimal('volatile_organic_compounds_voc_kg', 12, 4)->default(0);
            $table->boolean('permit_limit_exceeded')->default(false);
            $table->text('mitigation_action')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'monitoring_date']);
        });

        // 5. Biodiversity & Land Use (TNFD LEAP)
        Schema::create('biodiversity_sites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('site_name');
            $table->boolean('is_near_protected_area')->default(false);
            $table->string('protected_area_name')->nullable();
            $table->decimal('distance_to_protected_area_km', 8, 2)->nullable();
            $table->string('tnfd_leap_stage', 20)->default('locate'); // locate, evaluate, assess, prepare
            $table->string('ecosystem_type')->nullable(); // wetland, forest, coastal, grassland
            $table->decimal('total_site_hectares', 10, 2)->default(0);
            $table->decimal('restored_habitat_hectares', 10, 2)->default(0);
            $table->text('nature_dependencies_description')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'tnfd_leap_stage']);
        });

        // 6. Materials & Product Footprint (Digital Product Passports - DPP)
        Schema::create('product_footprints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('product_name');
            $table->string('sku')->nullable();
            $table->string('dpp_token')->unique(); // Digital Product Passport unique identifier
            $table->decimal('carbon_footprint_kg_co2e', 12, 4)->default(0);
            $table->decimal('recycled_content_percentage', 5, 2)->default(0);
            $table->decimal('repairability_score', 4, 2)->nullable(); // 0.00 to 10.00 score
            $table->boolean('has_epd')->default(false); // Environmental Product Declaration
            $table->string('epd_registration_number')->nullable();
            $table->string('raw_materials_origin')->nullable();
            $table->json('lifecycle_stage_breakdown')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'sku']);
        });

        // 7. Climate Finance, Carbon Credits & Offsets
        Schema::create('carbon_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('project_name');
            $table->string('standard', 50)->default('Verra VCS'); // Verra VCS, Gold Standard, CDM, Puro.earth
            $table->string('project_type', 50)->default('reforestation'); // reforestation, renewable, cookstoves, dac
            $table->string('vintage_year', 4);
            $table->decimal('tonnes_purchased', 14, 4);
            $table->decimal('tonnes_retired', 14, 4)->default(0);
            $table->unsignedBigInteger('cost_per_tonne_cents')->default(0);
            $table->string('currency', 3)->default('USD');
            $table->string('registry_serial_number')->nullable();
            $table->string('status', 30)->default('active'); // active, retired, cancelled
            $table->date('retirement_date')->nullable();
            $table->string('retirement_beneficiary')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'status']);
        });

        // 8. IoT & Meter Telemetry
        Schema::create('iot_meter_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('device_id', 100);
            $table->string('meter_type', 30); // electricity, water, steam, gas
            $table->timestamp('reading_timestamp');
            $table->decimal('reading_value', 18, 4);
            $table->string('unit', 20); // kWh, m3, kg
            $table->boolean('is_anomaly')->default(false);
            $table->timestamps();

            $table->index(['device_id', 'reading_timestamp']);
            $table->index(['organization_id', 'meter_type']);
        });

        // ==========================================
        // E. SOCIAL MODULES
        // ==========================================

        // 9. Workforce Demographics
        Schema::create('workforce_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reporting_year', 4);
            $table->string('period', 20)->default('annual');
            $table->unsignedInteger('total_headcount')->default(0);
            $table->unsignedInteger('male_headcount')->default(0);
            $table->unsignedInteger('female_headcount')->default(0);
            $table->unsignedInteger('other_gender_headcount')->default(0);
            $table->decimal('female_leadership_pct', 5, 2)->default(0);
            $table->unsignedInteger('new_hires_count')->default(0);
            $table->unsignedInteger('departures_count')->default(0);
            $table->decimal('turnover_rate_pct', 5, 2)->default(0);
            $table->decimal('gender_pay_gap_pct', 5, 2)->default(0);
            $table->boolean('living_wage_compliant')->default(true);
            $table->timestamps();

            $table->index(['organization_id', 'reporting_year']);
        });

        // 10. Attendance & Overtime Monitoring (AttendanceHub Integration)
        Schema::create('attendance_summary_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_code', 50);
            $table->string('employee_name')->nullable();
            $table->date('work_date');
            $table->string('shift_name', 50)->default('General');
            $table->dateTime('check_in_time')->nullable();
            $table->dateTime('check_out_time')->nullable();
            $table->decimal('regular_hours', 5, 2)->default(8.00);
            $table->decimal('overtime_hours', 5, 2)->default(0.00);
            $table->boolean('rest_day_provided')->default(true);
            $table->boolean('excessive_ot_alert')->default(false); // OT > 2 hours/day triggers labor alert
            $table->string('attendance_hub_device_id')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'work_date']);
            $table->index(['employee_code', 'work_date']);
        });

        // 11. Health & Safety (OHS) Incidents
        Schema::create('ohs_incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('incident_number')->unique();
            $table->date('incident_date');
            $table->string('incident_type', 30); // near_miss, first_aid, lost_time, medical_treatment, fatality
            $table->string('severity', 20)->default('low'); // low, medium, high, critical
            $table->text('description');
            $table->string('location_within_facility')->nullable();
            $table->unsignedInteger('lost_work_days')->default(0);
            $table->text('root_cause')->nullable();
            $table->text('corrective_action_plan')->nullable();
            $table->string('status', 30)->default('open'); // open, investigating, resolved, closed
            $table->foreignId('investigated_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['organization_id', 'incident_type']);
        });

        // 12. Training & Development
        Schema::create('training_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('category', 50); // safety, human_rights, anti_corruption, esg_awareness, technical
            $table->decimal('duration_hours', 5, 2);
            $table->string('trainer_name')->nullable();
            $table->date('conducted_date');
            $table->unsignedInteger('attendee_count')->default(0);
            $table->boolean('attendance_synced_via_hub')->default(false);
            $table->timestamps();

            $table->index(['organization_id', 'category']);
        });

        // 13. Human Rights & Labor Auditing
        Schema::create('human_rights_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->date('assessment_date');
            $table->string('auditor_name')->nullable();
            $table->string('child_labor_risk', 20)->default('none'); // none, low, medium, high
            $table->string('forced_labor_risk', 20)->default('none');
            $table->boolean('freedom_of_association_respected')->default(true);
            $table->boolean('collective_bargaining_in_place')->default(true);
            $table->boolean('minimum_living_wage_paid')->default(true);
            $table->text('findings_summary')->nullable();
            $table->text('corrective_actions')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'assessment_date']);
        });

        // 14. Community & Social Impact (CSR)
        Schema::create('csr_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('sdg_tag', 20)->default('SDG 3'); // e.g. SDG 3 Good Health, SDG 4 Education
            $table->unsignedBigInteger('budget_cents')->default(0);
            $table->unsignedBigInteger('actual_spend_cents')->default(0);
            $table->string('currency', 3)->default('USD');
            $table->unsignedInteger('beneficiaries_count')->default(0);
            $table->string('location')->nullable();
            $table->text('impact_description')->nullable();
            $table->string('photo_evidence_url')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'sdg_tag']);
        });

        // 15. Whistleblower & Ethics Hotline
        Schema::create('whistleblower_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('case_token', 64)->unique(); // Secret access token for whistleblower
            $table->string('category', 50); // bribery, sexual_harassment, safety_violation, financial_fraud, discrimination
            $table->string('subject');
            $table->text('report_details');
            $table->string('status', 30)->default('submitted'); // submitted, under_investigation, resolved, dismissed
            $table->string('priority', 20)->default('high');
            $table->text('resolution_summary')->nullable();
            $table->foreignId('assigned_investigator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['case_token']);
            $table->index(['organization_id', 'status']);
        });

        // 16. Employee Surveys & Engagement
        Schema::create('employee_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('survey_type', 30)->default('pulse'); // pulse, enps, annual_climate
            $table->date('launch_date');
            $table->date('close_date')->nullable();
            $table->unsignedInteger('total_responses')->default(0);
            $table->integer('enps_score')->default(0); // -100 to +100
            $table->decimal('satisfaction_rating', 3, 2)->default(0); // 1.00 to 5.00
            $table->string('status', 20)->default('active');
            $table->timestamps();

            $table->index(['organization_id', 'survey_type']);
        });

        // 17. Product Responsibility & Customer Welfare
        Schema::create('customer_complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('complaint_number')->unique();
            $table->string('customer_name')->nullable();
            $table->string('category', 50); // safety, quality, privacy, chemical_compliance
            $table->string('severity', 20)->default('medium');
            $table->text('description');
            $table->string('status', 30)->default('open');
            $table->text('resolution_notes')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'category', 'status']);
        });

        // ==========================================
        // F. GOVERNANCE MODULES
        // ==========================================

        // 18. Board & Governance Data
        Schema::create('board_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('designation'); // Chairman, Independent Director, Managing Director, Executive Director
            $table->boolean('is_independent')->default(false);
            $table->string('gender', 20)->default('male');
            $table->date('appointment_date')->nullable();
            $table->json('committee_memberships')->nullable(); // ['audit', 'esg', 'nomination', 'remuneration']
            $table->boolean('esg_oversight_role')->default(false);
            $table->boolean('executive_remuneration_esg_linked')->default(false);
            $table->decimal('meeting_attendance_percentage', 5, 2)->default(100.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['organization_id', 'is_independent']);
        });

        // 19. Policies & Code of Conduct
        Schema::create('corporate_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('policy_type', 50); // code_of_conduct, anti_bribery, environmental, whistleblower, data_privacy
            $table->string('version')->default('1.0');
            $table->date('effective_date');
            $table->date('next_review_date')->nullable();
            $table->string('document_url')->nullable();
            $table->boolean('mandatory_acknowledgment')->default(true);
            $table->unsignedInteger('acknowledgments_count')->default(0);
            $table->timestamps();

            $table->index(['organization_id', 'policy_type']);
        });

        // 20. Policy Acknowledgments
        Schema::create('policy_acknowledgments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('corporate_policy_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('acknowledged_at');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->unique(['corporate_policy_id', 'user_id']);
        });

        // 21. Compliance Obligations & Permits
        Schema::create('compliance_obligations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title'); // e.g. Factory Environmental Clearance Certificate (ECC), Fire License
            $table->string('regulatory_authority'); // DoE, Fire Service, Factory Inspectorate (DIFE)
            $table->string('permit_number')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date');
            $table->string('status', 30)->default('active'); // active, expiring_soon, expired, renewing
            $table->unsignedBigInteger('fine_penalty_cents')->default(0);
            $table->string('document_url')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'expiry_date', 'status']);
        });

        // 22. Anti-Corruption, Ethics & Tax
        Schema::create('anti_corruption_trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('year', 4);
            $table->unsignedInteger('eligible_headcount')->default(0);
            $table->unsignedInteger('completed_headcount')->default(0);
            $table->decimal('completion_rate_pct', 5, 2)->default(0);
            $table->unsignedInteger('confirmed_corruption_incidents')->default(0);
            $table->unsignedBigInteger('political_contributions_cents')->default(0);
            $table->timestamps();

            $table->index(['organization_id', 'year']);
        });

        // 23. Data Privacy & Cybersecurity
        Schema::create('cybersecurity_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->date('audit_date');
            $table->string('standard', 50)->default('ISO 27001'); // ISO 27001, SOC 2, NIST, GDPR
            $table->string('auditor_firm')->nullable();
            $table->unsignedInteger('vulnerabilities_identified')->default(0);
            $table->unsignedInteger('critical_severity_count')->default(0);
            $table->string('certification_status', 30)->default('certified'); // certified, action_required, lapsed
            $table->date('certificate_expiry_date')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'standard']);
        });

        // 24. Internal Controls over ESG Data
        Schema::create('internal_controls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('control_code', 30)->unique();
            $table->string('title');
            $table->string('esg_pillar', 20)->default('environmental'); // environmental, social, governance
            $table->string('frequency', 30)->default('quarterly'); // monthly, quarterly, annual
            $table->string('control_owner_role')->default('esg_manager');
            $table->string('test_status', 30)->default('effective'); // effective, deficient, not_tested
            $table->foreignId('sign_off_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('last_tested_date')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'esg_pillar', 'test_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_controls');
        Schema::dropIfExists('cybersecurity_audits');
        Schema::dropIfExists('anti_corruption_trainings');
        Schema::dropIfExists('compliance_obligations');
        Schema::dropIfExists('policy_acknowledgments');
        Schema::dropIfExists('corporate_policies');
        Schema::dropIfExists('board_members');
        Schema::dropIfExists('customer_complaints');
        Schema::dropIfExists('employee_surveys');
        Schema::dropIfExists('whistleblower_cases');
        Schema::dropIfExists('csr_projects');
        Schema::dropIfExists('human_rights_assessments');
        Schema::dropIfExists('training_programs');
        Schema::dropIfExists('ohs_incidents');
        Schema::dropIfExists('attendance_summary_logs');
        Schema::dropIfExists('workforce_snapshots');
        Schema::dropIfExists('iot_meter_readings');
        Schema::dropIfExists('carbon_credits');
        Schema::dropIfExists('product_footprints');
        Schema::dropIfExists('biodiversity_sites');
        Schema::dropIfExists('air_emissions');
        Schema::dropIfExists('waste_manifests');
        Schema::dropIfExists('water_records');
        Schema::dropIfExists('utility_bills');
    }
};
