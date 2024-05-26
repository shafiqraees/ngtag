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
        Schema::create('otp_processes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('otp_code')->comment('4 digit random code');
            $table->uuid('otp_id')->comment('sequence idm 10 digit <date:min:sec:ms>');
            $table->unsignedInteger('msisdn')->comment('--3001234567 format without 92');
            $table->string('otp_type', 6)->nullable()->comment('CORP=corporate, IND=Individual');
            $table->string('channel', 10)->nullable()->comment('SMS/IVR/USSD/CRM/App/Web');
            $table->dateTime('created_at')->default(now());
            $table->dateTime('verify_date')->nullable();
            $table->dateTime('expiration_time')->nullable()->comment('Expiration time for OTP');
            $table->tinyInteger('status')->default(0)->comment('0 = pending, 1=verified, 2=failed, 3=expired');
            $table->softDeletes();

            $table->index('msisdn');
            $table->index('otp_code');
            $table->index('otp_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_processes');
    }
};
