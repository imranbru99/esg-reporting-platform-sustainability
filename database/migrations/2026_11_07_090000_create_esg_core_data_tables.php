<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Frameworks Library
        Schema::create('frameworks', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // gri, issb, csrd, tcfd, tnfd, sasb, cdp, sdg, ghg_protocol, sbti, brsr, bsec
            $table->string('name');
            $table->string('version')->default('2024');
            $table->string('publisher');
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(true);
            $table->boolean('is_active')->default(true);
            $table->string('documentation_url')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'slug']);
        });

        // 2. Framework Disclosures
        Schema::create('framework_disclosures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('framework_id')->constrained()->cascadeOnDelete();
            $table->string('code'); // e.g. GRI 305-1, IFRS S2 para 29a, ESRS E1-6
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category', 20)->default('environmental'); // environmental, social, governance, general
            $table->string('sector')->nullable(); // general, textiles, banking, oil_gas, agriculture
            $table->string('requirement_type', 30)->default('quantitative'); // quantitative, qualitative, mixed
            $table->boolean('is_mandatory')->default(false);
            $table->text('guidance_notes')->nullable();
            $table->timestamps();

            $table->unique(['framework_id', 'code']);
            $table->index(['framework_id', 'category']);
        });

        // 3. Metric Definition Library
        Schema::create('metrics', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // e.g. scope1_ghg_direct, electricity_consumption, workforce_female_ratio
            $table->string('code')->nullable();
            $table->string('name');
            $table->string('category', 20); // environmental, social, governance
            $table->string('subcategory', 50); // ghg_scope1, energy, water, waste, diversity, ohs, board
            $table->string('unit_type', 30)->default('mass'); // mass, energy, volume, count, percentage, currency, ratio, text
            $table->string('default_unit', 30)->default('tCO2e'); // tCO2e, kWh, m3, headcount, %, USD
            $table->string('aggregation_method', 30)->default('sum'); // sum, average, latest, weighted
            $table->text('description')->nullable();
            $table->text('calculation_formula')->nullable();
            $table->boolean('is_standard')->default(true);
            $table->timestamps();

            $table->index(['category', 'subcategory']);
        });

        // 4. Cross-Framework Disclosure Mapping
        Schema::create('disclosure_metric_maps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('framework_disclosure_id')->constrained()->cascadeOnDelete();
            $table->foreignId('metric_id')->constrained()->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->decimal('formula_weight', 5, 2)->default(1.00);
            $table->timestamps();

            $table->unique(['framework_disclosure_id', 'metric_id']);
        });

        // 5. Emission Factor Libraries
        Schema::create('emission_factor_libraries', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // DEFRA 2024, US EPA 2024, IPCC AR6, IEA 2024
            $table->string('publisher');
            $table->string('year', 4);
            $table->string('version')->default('1.0');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 6. Emission Factors
        Schema::create('emission_factors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('library_id')->constrained('emission_factor_libraries')->cascadeOnDelete();
            $table->string('category', 50); // fuels, electricity, travel, freight, waste, materials, spend
            $table->string('activity_name'); // e.g. Diesel (100% mineral), Electricity Grid - Bangladesh, Air Travel Long-Haul
            $table->string('scope', 30); // scope1, scope2_location, scope2_market, scope3_cat1 ... scope3_cat15
            $table->decimal('factor_value', 18, 8); // e.g. 2.68787000
            $table->string('factor_unit', 50); // kgCO2e/L, kgCO2e/kWh, kgCO2e/tonne.km, kgCO2e/USD
            $table->decimal('co2_factor', 18, 8)->nullable();
            $table->decimal('ch4_factor', 18, 8)->nullable();
            $table->decimal('n2o_factor', 18, 8)->nullable();
            $table->string('source_reference')->nullable();
            $table->decimal('uncertainty_percentage', 5, 2)->default(5.00);
            $table->string('applicable_year', 4)->default('2024');
            $table->string('region_code', 10)->default('GLOBAL'); // GLOBAL, BGD, USA, GBR, EU
            $table->timestamps();

            $table->index(['scope', 'category']);
            $table->index(['activity_name', 'region_code']);
        });

        // 7. Data Collection Requests & Schedules
        Schema::create('data_collection_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reporting_year', 4);
            $table->string('reporting_period', 20)->default('annual'); // annual, Q1, Q2, Q3, Q4, monthly_1...12
            $table->string('title');
            $table->string('status', 30)->default('draft'); // draft, open, submitted, approved, locked
            $table->date('due_date')->nullable();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('locked_at')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'reporting_year', 'reporting_period']);
        });

        // 8. Metric Entries (Auditable Data Points)
        Schema::create('metric_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('metric_id')->constrained()->cascadeOnDelete();
            $table->foreignId('collection_request_id')->nullable()->constrained('data_collection_requests')->nullOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('activity_value', 18, 4);
            $table->string('activity_unit', 30);
            $table->decimal('normalized_value', 18, 4); // Converted to standard unit
            $table->decimal('calculated_co2e', 18, 6)->nullable(); // Total tCO2e if environmental
            $table->foreignId('emission_factor_id')->nullable()->constrained('emission_factors')->nullOnDelete();
            $table->json('factor_snapshot')->nullable(); // Captures factor_value, source, uncertainty at calculation time
            $table->string('status', 30)->default('draft'); // draft, submitted, approved, locked, restated
            $table->boolean('is_anomaly')->default(false);
            $table->string('anomaly_reason')->nullable();
            $table->text('notes')->nullable();
            $table->text('restatement_reason')->nullable();
            $table->foreignId('entered_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['organization_id', 'period_start', 'period_end']);
            $table->index(['metric_id', 'status']);
        });

        // 9. Evidence Vault & Document Storage
        Schema::create('evidence_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('metric_entry_id')->nullable()->constrained('metric_entries')->nullOnDelete();
            $table->string('title');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size_bytes')->default(0);
            $table->string('file_hash_sha256', 64)->nullable();
            $table->string('document_type', 50)->default('invoice'); // utility_bill, invoice, certificate, meter_log, audit_pack
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->longText('ocr_extracted_text')->nullable();
            $table->json('ai_metadata')->nullable();
            $table->foreignId('uploaded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['organization_id', 'document_type']);
        });

        // 10. Regulation Watch (Scraped via laravel-ai-catalog)
        Schema::create('regulation_watches', function (Blueprint $table) {
            $table->id();
            $table->string('framework_slug');
            $table->string('source_url')->nullable();
            $table->string('headline');
            $table->longText('raw_text')->nullable();
            $table->text('ai_summary')->nullable();
            $table->string('impact_level', 20)->default('medium'); // low, medium, high, critical
            $table->json('action_recommendations')->nullable();
            $table->string('status', 30)->default('pending_review'); // pending_review, acknowledged, action_created, archived
            $table->foreignId('reviewed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['framework_slug', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regulation_watches');
        Schema::dropIfExists('evidence_documents');
        Schema::dropIfExists('metric_entries');
        Schema::dropIfExists('data_collection_requests');
        Schema::dropIfExists('emission_factors');
        Schema::dropIfExists('emission_factor_libraries');
        Schema::dropIfExists('disclosure_metric_maps');
        Schema::dropIfExists('metrics');
        Schema::dropIfExists('framework_disclosures');
        Schema::dropIfExists('frameworks');
    }
};
