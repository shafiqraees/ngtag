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
        Schema::create('subscribers_0', function (Blueprint $table) {
            $table->id()->comment('AUTO_INCREMENT');
            $table->unsignedInteger('msisdn')->comment('--3001234567 format without');
            $table->tinyInteger('opr_type')->nullable()->comment('--Ethio -prepad =1,ethio_post=2');
            $table->string('name_tag', 15)->nullable()->comment('nametag without prefix #kfc');
            $table->string('tag_no', 10)->nullable()->comment('tagno without prefix #532');
            $table->string('tag_type', 10)->nullable()->comment('1=VIP,2=Golden,3=Silver,4=Custom,5=normal');
            $table->unsignedTinyInteger('tag_digits')->comment('2 digits');
            $table->string('tag_no_price', 10)->nullable()->comment('price');
            $table->string('service_fee', 4)->nullable()->comment('price');
            $table->string('payment_method', 10)->nullable()->comment('MAAPI');
            $table->tinyInteger('payment_status')->default(0)->comment('0=pending for payment, 1=payment processed');
            $table->dateTime('created_date')->default(now())->comment('first time subscription');
            $table->dateTime('payment_date')->default(now())->comment('payment process date by MA API');
            $table->dateTime('sub_date')->default(now())->comment('last time subscription');
            $table->string('sub_lang', 4)->nullable()->comment('ENG/ETH');
            $table->string('channel', 8)->nullable()->comment('SMS/IVR/USSD/CRM/App');
            $table->dateTime('unsub_date')->nullable();
            $table->string('unsub_channel', 10)->nullable();
            $table->dateTime('charge_dt')->nullable();
            $table->dateTime('next_charge_dt')->nullable();
            $table->dateTime('schedule_hr_start')->nullable()->comment('start hour , 10:00');
            $table->dateTime('schedule_hr_end')->nullable()->comment('end hour , 5:00 pm');
            $table->tinyInteger('status')->nullable()->comment('0 = reserved, 1=active, 2=pending for approval, 3=suspend due to non-billing, 6=unsub, 7=user turn off service, 11=in churnout');
            $table->dateTime('status_update_date')->nullable();
            $table->tinyInteger('service_id')->nullable()->comment('1=daily, 7=weekly, 30=monthly');

            //$table->primary('msisdn');
            $table->index('next_charge_dt');
            $table->index('service_id');
            $table->index('opr_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
