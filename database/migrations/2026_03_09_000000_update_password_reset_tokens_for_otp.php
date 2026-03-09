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
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            // Add OTP related columns
            $table->string('otp')->nullable()->after('token');
            $table->timestamp('otp_expires_at')->nullable()->after('otp');
            $table->boolean('otp_verified')->default(false)->after('otp_expires_at');
            $table->timestamp('verified_at')->nullable()->after('otp_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dropColumn(['otp', 'otp_expires_at', 'otp_verified', 'verified_at']);
        });
    }
};
