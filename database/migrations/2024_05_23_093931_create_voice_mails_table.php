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
        Schema::create('voice_mails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('corp_customer_account_id')->nullable()->comment('ID of the table corp_subscribers');
            $table->unsignedInteger('msisdn');
            $table->unsignedInteger('caller_no');
            $table->string('voicemail_file', 30)->comment('Filename');
            $table->tinyInteger('user_type')->unsigned()->comment('1=corporate, 2=normal');
            $table->dateTime('created_at')->default(now());
            $table->dateTime('listen_date')->nullable();
            $table->tinyInteger('status')->nullable()->comment('0 = pending, 1=listen, 2=expire');
            $table->softDeletes();

            $table->index('msisdn');
            $table->index('caller_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voice_mails');
    }
};
