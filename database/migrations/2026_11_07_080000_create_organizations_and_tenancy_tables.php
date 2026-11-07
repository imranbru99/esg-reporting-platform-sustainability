<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Organizations (Tenants)
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('legal_name')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('domain')->nullable()->unique();
            $table->string('industry')->default('general');
            $table->string('country_code', 3)->default('USA');
            $table->string('currency_code', 3)->default('USD');
            $table->string('timezone')->default('UTC');
            $table->string('locale')->default('en');
            $table->string('consolidation_method')->default('operational_control'); // operational_control, financial_control, equity_share
            $table->string('status')->default('active'); // active, trial, suspended, archived
            $table->timestamp('trial_ends_at')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('brand_color')->default('#059669'); // emerald-600 default ESG theme
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'industry']);
        });

        // 2. Legal Entities (Subsidiaries / Group Companies)
        Schema::create('legal_entities', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('country_code', 3)->default('USA');
            $table->decimal('ownership_percentage', 5, 2)->default(100.00);
            $table->string('consolidation_approach')->default('operational_control');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organization_id', 'is_active']);
        });

        // 3. Branches / Sites / Facilities
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('legal_entity_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('facility_type')->default('office'); // factory, office, warehouse, retail, data_center, farm, mine
            $table->string('country_code', 3)->default('USA');
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('gross_floor_area', 12, 2)->nullable();
            $table->string('area_unit', 10)->default('sqm'); // sqm, sqft
            $table->unsignedInteger('headcount')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organization_id', 'facility_type']);
        });

        // 4. Departments
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->unsignedBigInteger('head_user_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['organization_id', 'is_active']);
        });

        // 5. Business Units
        Schema::create('business_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['organization_id', 'is_active']);
        });

        // 6. User Table Enhancements for Multi-Tenancy & Roles
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id')->unique();
            $table->foreignId('organization_id')->nullable()->after('uuid')->constrained()->nullOnDelete();
            $table->foreignId('legal_entity_id')->nullable()->after('organization_id')->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->after('legal_entity_id')->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->after('branch_id')->constrained()->nullOnDelete();
            $table->string('phone')->nullable()->after('email');
            $table->string('job_title')->nullable()->after('phone');
            $table->string('avatar_url')->nullable()->after('job_title');
            $table->string('locale')->default('en')->after('avatar_url');
            $table->string('timezone')->default('UTC')->after('locale');
            $table->boolean('is_active')->default(true)->after('timezone');
            $table->text('two_factor_secret')->nullable()->after('password');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
            $table->json('preferences')->nullable()->after('two_factor_confirmed_at');
            $table->timestamp('last_login_at')->nullable()->after('preferences');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->softDeletes()->after('updated_at');

            $table->index(['organization_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropForeign(['legal_entity_id']);
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['department_id']);
            $table->dropColumn([
                'uuid',
                'organization_id',
                'legal_entity_id',
                'branch_id',
                'department_id',
                'phone',
                'job_title',
                'avatar_url',
                'locale',
                'timezone',
                'is_active',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
                'preferences',
                'last_login_at',
                'last_login_ip',
                'deleted_at',
            ]);
        });

        Schema::dropIfExists('business_units');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('legal_entities');
        Schema::dropIfExists('organizations');
    }
};
