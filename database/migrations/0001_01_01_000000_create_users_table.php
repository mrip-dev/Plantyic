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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable(); // REMOVED ->after('email')
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->nullable();
            $table->string('company_name')->nullable(); // REMOVED ->after('phone')
            $table->string('trade_license')->nullable(); // REMOVED ->after('company_name')
            $table->string('emirates_id')->nullable(); // REMOVED ->after('trade_license')

            // Profile Status
            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->boolean('profile_completed')->default(false);

            // Financial (for vendors) - FIXED data types
            $table->decimal('wallet_balance', 10, 2)->default(0); // Use decimal for money
            $table->integer('loyalty_points')->default(0); // Use integer for points

            // Add other fields from your model
            $table->string('user_type')->default('customer')->nullable();
            $table->string('vendor_type')->nullable();

            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_designation')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->json('service_area')->nullable();
            $table->text('address')->nullable();
            $table->string('photo')->nullable();
            $table->string('id_copy_front')->nullable();
            $table->string('id_copy_back')->nullable();
            $table->string('trade_license_copy')->nullable();

            $table->string('status')->default('pending');

            $table->timestamp('last_login_at')->nullable();
            $table->string('device_token')->nullable();

            $table->rememberToken();
            $table->timestamps();

        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};