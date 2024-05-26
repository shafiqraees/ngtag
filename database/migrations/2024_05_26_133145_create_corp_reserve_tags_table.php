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
        Schema::create('corp_reserve_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('corp_customer_account_id')->nullable()->comment('id of table =corp_customer_account');
            $table->unsignedInteger('phone_number')->comment('--3001234567 format without 252');
            $table->unsignedInteger('msisdn')->comment('--3001234567 format without 252');
            $table->unsignedInteger('corp_tag_list_id')->nullable()->comment('id of selected tagno, id of table =corp_taglist');
            $table->string('payment_method', 15)->nullable()->comment('MOBIL_WALLET, CASH');
            $table->tinyInteger('payment_status')->default(0)->comment('0=pending for payment, 1=payment success, 2=Payment Failed, 3=expired payment timeline');
            $table->dateTime('payment_date')->nullable()->comment('payment process attempt date using wallet');
            $table->dateTime('expiry_date')->nullable()->comment('expiry date of number if any');
            $table->timestamp('deleted_at')->nullable();
            $table->dateTime('created_date')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'))->comment('first time subscription');
            $table->tinyInteger('status')->default(0)->comment('0=resver due to documents, 2=buy, 1=active, 3=pending for payment, 4=expired_docs, 5=expird_payment, 6= blockby admin');
            $table->dateTime('status_update_date')->nullable()->comment('last status change date');

            $table->index('msisdn', 'idx_corp_customer_accounts');
            $table->index('corp_tag_list_id', 'idx_corp_customer_tag_no'); // Assuming the correct field is corp_tag_list_id
            $table->index('payment_method', 'idx_corp_customer_tagType'); // Assuming the correct field is payment_method

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corp_reserve_tags');
    }
};
