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
        Schema::create('corp_subscribers', function (Blueprint $table) {
            $table->id()->unsigned()->comment('auto INCREMENT');
            $table->unsignedInteger('account_id')->nullable()->comment('id of table =corp_customer_account');
            $table->unsignedInteger('msisdn')->comment('--3001234567 format without 252');
            $table->unsignedInteger('tag_id')->comment('id of selected tagno, id of table =corp_taglist');
            $table->uuid('corp_subscriber_id')->comment('--384a2409-028f-42cd-b3e1-527d769ce490 format without can be used in url');
            $table->string('name_tag', 15)->nullable()->comment('nametag without prefix kfc');
            $table->string('tag_no', 10)->nullable()->comment('tagno without prefix 532');
            $table->string('tag_type', 10)->nullable()->comment('tagtype= VIP,golden, silver');
            $table->unsignedTinyInteger('tag_length')->nullable()->comment('2 or more digits');
            $table->string('tag_no_price', 10)->nullable()->comment('price -5000 onetime payment');
            $table->string('payment_method', 15)->nullable()->comment('MOBIL_WALLET, CASH');
            $table->tinyInteger('payment_status')->default(0)->comment('0=pending for payment, 1=payment success,2=Payment Failed,3=expired payment timeline');
            $table->dateTime('payment_date')->nullable()->comment('payment process attempt date using wallet');
            $table->dateTime('expiry_date')->nullable()->comment('expiry date of number if any');
            $table->string('service_fee', 4)->nullable()->comment('monthly service fee value');
            $table->dateTime('sub_date')->nullable()->comment('subscription date, after payment successfully paid');
            $table->dateTime('unsub_date')->nullable();
            $table->string('unsub_channel', 10)->nullable();
            $table->dateTime('charge_dt')->nullable()->comment('monthly service fee charging date');
            $table->dateTime('next_charge_dt')->nullable()->comment('next charging date');
            $table->dateTime('created_date')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'))->comment('account creation date');
            $table->tinyInteger('voic_email')->default(0)->comment('0= off 1=ON');
            $table->tinyInteger('incoming_call_status')->default(0)->comment('0= off 1=ON');
            $table->dateTime('incall_start_dt')->nullable()->comment('date-time, hours to start incoming calls');
            $table->dateTime('incall_end_dt')->nullable()->comment('date-time to stop/end incoming calls');
            $table->tinyInteger('status')->default(0)->comment('0= pending for payment, 1=active,2=pending for doc approval,3=unsub,4=churn out monthly fee pending,5= block,6=expired');
            $table->dateTime('status_update_date')->nullable()->comment('last status change date');
            $table->tinyInteger('service_id')->default(30)->comment('7=weekly, 30=monthly');

            //$table->primary('id');
            $table->index('msisdn', 'idx_corp_customer_account');
            $table->index('tag_no', 'idx_corp_customer_tagno');
            $table->index('tag_type', 'idx_corp_customer_tagType');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corp_subscribers');
    }
};
