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
        Schema::create('call_back_lists', function (Blueprint $table) {
            $table->id()->comment('AUTO_INCREMENT');
            $table->unsignedInteger('msisdn')->comment('The MSISDN to be blacklisted');
            $table->unsignedInteger('caller_no')->comment('The caller number to block');
            $table->string('channel', 10)->nullable()->comment('The channel through which the blacklisting occurred');
            $table->dateTime('created_at')->default(now())->comment('The timestamp of when the blacklisting occurred');

            $table->index('msisdn');
            $table->index('caller_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_back_lists');
    }
};
