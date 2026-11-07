<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Report Project / Campaign
        Schema::create('report_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('reporting_period', 4);
            $table->string('primary_framework', 50)->default('gri');
            $table->string('boundary_type', 50)->default('operational_control');
            $table->string('baseline_year', 4)->default('2022');
            $table->string('language', 10)->default('en');
            $table->string('currency', 3)->default('USD');
            $table->string('status', 30)->default('draft'); // draft, in_review, approved, published, locked
            $table->string('sha256_hash', 64)->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'reporting_period', 'status']);
        });

        // 2. Outline & Sections
        Schema::create('report_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('report_sections')->cascadeOnDelete();
            $table->string('title');
            $table->string('section_key', 50);
            $table->integer('order_index')->default(0);
            $table->longText('content_markdown')->nullable();
            $table->string('tone', 40)->default('authoritative');
            $table->string('status', 30)->default('draft'); // draft, generated, in_review, approved
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['report_project_id', 'order_index']);
        });

        // 3. Strict Narrative Traceability Citations
        Schema::create('report_narrative_citations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_section_id')->constrained()->cascadeOnDelete();
            $table->string('citation_token', 50); // e.g. {{CITE_1}}
            $table->foreignId('metric_entry_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('raw_value', 18, 4)->nullable();
            $table->string('formatted_value', 100);
            $table->string('label');
            $table->foreignId('source_evidence_id')->nullable()->constrained('evidence_documents')->nullOnDelete();
            $table->foreignId('contributor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['report_section_id', 'citation_token']);
        });

        // 4. Report Sign-Offs & Governance Approval Chain
        Schema::create('report_signoffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role_title', 100); // Sustainability Lead, CFO, CEO, Board Member
            $table->string('signature_hash', 64);
            $table->text('comments')->nullable();
            $table->timestamp('signed_at');
            $table->timestamps();

            $table->index(['report_project_id', 'user_id']);
        });

        // 5. Gap Analyses & Readiness Scoring
        Schema::create('gap_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('framework_slug', 50);
            $table->string('reporting_year', 4);
            $table->decimal('overall_readiness_score', 5, 2)->default(0);
            $table->integer('completed_disclosures')->default(0);
            $table->integer('total_required_disclosures')->default(0);
            $table->json('missing_disclosures')->nullable();
            $table->json('details_json')->nullable();
            $table->timestamp('generated_at');
            $table->timestamps();

            $table->index(['organization_id', 'framework_slug', 'reporting_year']);
        });

        // 6. ESG Internal Scorecards
        Schema::create('esg_scorecards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('reporting_year', 4);
            $table->decimal('overall_score', 5, 2)->default(0);
            $table->decimal('environmental_score', 5, 2)->default(0);
            $table->decimal('social_score', 5, 2)->default(0);
            $table->decimal('governance_score', 5, 2)->default(0);
            $table->string('rating_grade', 5)->default('BBB'); // AAA, AA, A, BBB, BB, B, CCC
            $table->string('methodology', 100)->default('Weighted Pillar Algorithm (E:40%, S:30%, G:30%)');
            $table->json('strengths')->nullable();
            $table->json('improvement_areas')->nullable();
            $table->timestamp('generated_at');
            $table->timestamps();

            $table->index(['organization_id', 'reporting_year']);
        });

        // 7. AI ESG Assistant Chat
        Schema::create('ai_chat_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('ai_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_chat_conversation_id')->constrained()->cascadeOnDelete();
            $table->string('role', 20); // user, assistant
            $table->longText('content');
            $table->json('citations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_chat_messages');
        Schema::dropIfExists('ai_chat_conversations');
        Schema::dropIfExists('esg_scorecards');
        Schema::dropIfExists('gap_analyses');
        Schema::dropIfExists('report_signoffs');
        Schema::dropIfExists('report_narrative_citations');
        Schema::dropIfExists('report_sections');
        Schema::dropIfExists('report_projects');
    }
};
