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
        Schema::create('corp_customer_accounts', function (Blueprint $table) {
            $table->id()->unsigned()->comment('auto INCREMENT');
            $table->uuid('customer_account_id')->comment('--384a2409-028f-42cd-b3e1-527d769ce490 format without can be used in url');
            $table->string('phone_number')->comment('--3001234567 format without 252 as primary number');
            $table->string('comp_name', 30)->comment('tagno without prefix ABC');
            $table->string('comp_brand', 20)->nullable()->comment('Brand name');
            $table->string('comp_industry', 30)->nullable()->comment('industry name');
            $table->string('comp_country', 50)->nullable()->comment('country');
            $table->string('comp_state', 50)->nullable()->comment('state');
            $table->string('comp_city', 50)->nullable()->comment('city');
            $table->string('comp_addr', 50)->nullable()->comment('address');
            $table->string('comp_reg_no', 20)->nullable()->comment('REG no 990023');
            $table->string('website', 100)->nullable()->comment('site address');
            $table->string('contact_fname', 50)->nullable()->comment('company contact person name');
            $table->string('contact_lname', 50)->nullable()->comment('company contact person name');
            $table->string('contact_no', 13)->nullable()->comment('mobile no of contact person');
            $table->string('email', 50)->nullable()->comment('email');
            $table->string('ntn', 20)->nullable()->comment('NTN number');
            $table->string('comp_logo_file_name', 20)->nullable()->comment('company logo file name');
            $table->string('comp_doc_name1', 30)->nullable()->comment('document name1 NTN');
            $table->string('file_doc_name1', 50)->nullable()->comment('document1 filename');
            $table->string('comp_doc_name2', 30)->nullable()->comment('CNIC');
            $table->string('file_doc_name2', 50)->nullable()->comment('document2 filename');
            $table->dateTime('docs_upload_date')->nullable();
            $table->tinyInteger('doc_approval_status')->default(0)->comment('0=pending for approval, 1=Approved, 2=Rejected');
            $table->dateTime('doc_approval_date')->nullable()->comment('document approval process date');
            $table->unsignedInteger('doc_approval_admin_id')->nullable()->comment('document approval officer ID from table of users');
            $table->string('doc_approval_comments', 100)->nullable()->comment('Comments by approval officer');
            $table->dateTime('created_date')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'))->comment('first time subscription');
            $table->string('username', 15)->comment('login username for web portal');
            $table->string('password', 200)->comment('password for web portal');
            $table->unsignedInteger('login_attempts')->default(0)->comment('WRONG password attempts');
            $table->date('passwd_change_date')->nullable()->comment('password change date');
            $table->string('user_lang', 4)->default('ENG')->comment('ENG/ETH');
            $table->string('channel', 8)->nullable()->comment('CRM/App/WEB');
            $table->tinyInteger('status')->default(0)->comment('0 = pending for approval, 1= Approved, 2=rejected, 4=Blocked by Admin, 5=suspend by non-payment, 6=block due to wrong password attempt');
            $table->dateTime('status_update_date')->nullable();

            // Indexes
            //$table->primary('id');
            $table->index('username', 'idx_corp_customer_account_username');
            $table->index('msisdn', 'idx_corp_customer_account_msisdn');
            $table->index('status', 'idx_corp_customer_account_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corp_customer_accounts');
    }
};
