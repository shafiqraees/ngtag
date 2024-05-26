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
        Schema::create('tag_list_premiums', function (Blueprint $table) {
            $table->id()->comment('AUTO_INCREMENT');
            $table->string('tag_name', 15)->nullable()->comment('tag name');
            $table->string('tag_no', 15)->nullable()->comment('tag code');
            $table->string('tag_name_price', 10)->nullable()->comment('price');
            $table->string('service_fee', 4)->nullable()->comment('service fee');
            $table->tinyInteger('tag_digits')->nullable()->comment('length of tags');
            $table->string('tag_type', 15)->nullable()->comment('1=VIP, 2=Gold, 3=Silver, 4=Normal');
            $table->dateTime('created_date')->default(now());
            $table->tinyInteger('status')->nullable()->comment('1=available, 2=sold, 3=reserve, 3');

            $table->index('id');
            $table->index('tag_name');
            $table->index('tag_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_list_premiums');
    }
};
