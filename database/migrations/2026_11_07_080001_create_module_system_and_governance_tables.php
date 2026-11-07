<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Module Catalog
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // e.g. 'carbon', 'energy', 'water', 'crm'
            $table->string('name');
            $table->string('category'); // platform, frameworks, strategy, environmental, social, governance, supply_chain, assurance, reporting, business_ops
            $table->text('description')->nullable();
            $table->string('version')->default('1.0.0');
            $table->string('icon')->default('cube');
            $table->boolean('is_core')->default(false); // Core modules cannot be toggled OFF
            $table->json('dependencies')->nullable(); // Array of module slugs this module requires
            $table->boolean('default_enabled')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['category', 'is_core']);
        });

        // 2. Tenant Module Toggles
        Schema::create('organization_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('module_slug');
            $table->boolean('is_enabled')->default(true);
            $table->json('settings')->nullable();
            $table->timestamp('enabled_at')->nullable();
            $table->timestamp('disabled_at')->nullable();
            $table->timestamps();

            $table->unique(['organization_id', 'module_slug']);
            $table->foreign('module_slug')->references('slug')->on('modules')->cascadeOnDelete();
            $table->index(['organization_id', 'is_enabled']);
        });

        // 3. Branch / Site Specific Module Toggles
        Schema::create('branch_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('module_slug');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();

            $table->unique(['branch_id', 'module_slug']);
            $table->foreign('module_slug')->references('slug')->on('modules')->cascadeOnDelete();
        });

        // 4. Commercial Plans & Tier Limits
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // starter, professional, enterprise, consultancy
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('monthly_price_cents')->default(0);
            $table->unsignedInteger('annual_price_cents')->default(0);
            $table->string('currency', 3)->default('USD');
            $table->json('included_modules'); // JSON array of module slugs
            $table->json('limits'); // { max_users: 10, max_entities: 2, max_ai_credits: 500, max_reports_per_month: 5 }
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Industry Presets
        Schema::create('presets', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // manufacturing, garment_textile, banking, etc.
            $table->string('name');
            $table->string('industry');
            $table->text('description')->nullable();
            $table->json('included_modules'); // JSON array of module slugs
            $table->string('recommended_for')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 6. Audit Trail & Immutable Activity Log
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('auditable_type')->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->string('event'); // created, updated, deleted, approved, locked, restated, login, impersonated
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->text('url')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'event']);
            $table->index(['auditable_type', 'auditable_id']);
        });

        // 7. Platform & Organization Settings
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('organization_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->json('value')->nullable();
            $table->timestamps();

            $table->unique(['organization_id', 'key']);
        });

        // 8. Notification Templates
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->string('key'); // e.g. data_request_reminder, report_approved
            $table->string('name');
            $table->string('channel')->default('email'); // email, sms, whatsapp, database
            $table->string('locale')->default('en');
            $table->string('subject')->nullable();
            $table->text('body');
            $table->json('variables')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'key', 'channel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('organization_settings');
        Schema::dropIfExists('platform_settings');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('presets');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('branch_modules');
        Schema::dropIfExists('organization_modules');
        Schema::dropIfExists('modules');
    }
};
