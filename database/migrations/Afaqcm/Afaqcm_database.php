<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('premium_until')->nullable();
        });

        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('has_free_plan')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained()->cascadeOnDelete();
            $table->string('name_en');
            $table->string('name_ar');
            $table->decimal('weight_percentage', 5, 2)->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->integer('order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->string('name_en');
            $table->string('name_ar');
            $table->decimal('weight_percentage', 5, 2)->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->integer('order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name_en');
            $table->string('name_ar');
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->integer('order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('requires_attachment')->default(false);
            $table->timestamps();
        });

        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('guest_email')->nullable();
            $table->string('guest_name')->nullable();
            $table->string('organization')->nullable();
            $table->enum('status', ['draft', 'completed', 'in_progress', 'archived'])->default('draft');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('assessment_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('criterion_id')->constrained();
            $table->enum('response', ['yes', 'no', 'na']);
            $table->text('notes')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamps();

            $table->unique(['assessment_id','criterion_id'], 'unique_assessment_criterion');
        });

        Schema::create('assessment_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->integer('total_criteria')->default(0);
            $table->integer('applicable_criteria')->default(0);
            $table->integer('yes_count')->default(0);
            $table->integer('no_count')->default(0);
            $table->integer('na_count')->default(0);
            $table->decimal('score_percentage', 5, 2)->default(0);
            $table->decimal('weighted_score', 5, 2)->default(0);
            $table->timestamps();

            $table->index(['assessment_id', 'domain_id']);
            $table->index(['assessment_id', 'category_id']);
        });

        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('criterion_id');
            $table->text('action_ar');
            $table->text('action_en');
            $table->boolean('flag')->default(true);
            $table->timestamps();

            $table->foreign('criterion_id')->references('id')->on('criteria')->onDelete('cascade');
            $table->index(['criterion_id', 'flag']);
        });

        Schema::create('guest_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->string('session_id')->index();
            $table->string('name');
            $table->string('email');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_type', 50)->nullable();
            $table->string('browser', 100)->nullable();
            $table->string('browser_version', 50)->nullable();
            $table->string('operating_system', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->string('timezone', 50)->nullable();
            $table->string('isp', 255)->nullable();
            $table->json('session_data')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_activity')->nullable();
            $table->timestamps();

            $table->index('ip_address');
            $table->index('started_at');
            $table->index('last_activity');
        });

        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->enum('company_type', ['commercial', 'government', 'service'])->nullable();
            $table->string('company_name')->nullable();
            $table->string('city')->nullable();
            $table->string('position')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->string('country')->nullable();
            $table->string('industry')->nullable();
            $table->integer('company_size')->nullable();
            $table->year('established_year')->nullable();
            $table->decimal('annual_revenue', 15, 2)->nullable();
            $table->string('linkedin_profile')->nullable();
            $table->string('twitter_profile')->nullable();
            $table->string('facebook_profile')->nullable();
            $table->string('preferred_language', 5)->default('en');
            $table->string('timezone')->nullable();
            $table->json('communication_preferences')->nullable();
            $table->json('interests')->nullable();
            $table->string('how_did_you_hear')->nullable();
            $table->boolean('marketing_emails')->default(true);
            $table->boolean('newsletter_subscription')->default(false);
            $table->timestamps();

            $table->index('phone');
            $table->index('company_name');
            $table->index('city');
            $table->index('country');
            $table->index('industry');
        });

        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('plan_type', ['free', 'premium'])->default('free');
            $table->enum('status', ['active', 'expired', 'cancelled', 'pending'])->default('active');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->json('features')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancelled_reason')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['plan_type', 'status']);
            $table->index('expires_at');
        });

        Schema::create('tool_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tool_id')->constrained()->onDelete('cascade');
            $table->enum('plan_type', ['free', 'premium'])->default('free');
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->json('features')->nullable();
            $table->string('paddle_subscription_id')->nullable();
            $table->string('paddle_customer_id')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->json('paddle_data')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'tool_id']);
        });

        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('iban');
            $table->string('swift_code')->nullable();
            $table->string('currency')->default('SAR');
            $table->timestamps();
        });

        Schema::create('tool_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tool_id')->constrained()->cascadeOnDelete();
            $table->foreignId('account_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('organization')->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'quoted', 'done'])->default('pending');
            $table->timestamp('quotation_sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_requests');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('tool_subscriptions');
        Schema::dropIfExists('user_subscriptions');
        Schema::dropIfExists('user_details');
        Schema::dropIfExists('guest_sessions');
        Schema::dropIfExists('actions');
        Schema::dropIfExists('assessment_results');
        Schema::dropIfExists('assessment_responses');
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('criteria');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('domains');
        Schema::dropIfExists('tools');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('premium_until');
        });
    }
};
