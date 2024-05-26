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
        Schema::create('corp_tag_lists', function (Blueprint $table) {
            $table->id()->comment('AUTO_INCREMENT');
            $table->string('tag_name', 15)->nullable()->comment('tag name KFC');
            $table->string('tag_no', 15)->nullable()->comment('tag code 562');
            $table->string('tag_type', 15)->nullable()->comment('VIP, Golden');
            $table->string('tag_price', 10)->nullable()->comment('price');
            $table->string('service_fee', 4)->nullable()->comment('service fee');
            $table->tinyInteger('tag_digits')->nullable()->comment('length of tags 2,3,4');
            $table->dateTime('created_date')->default(now());
            $table->tinyInteger('status')->nullable()->comment('1=available, 2=sold, 3=reserved, 4=');
            $table->string('comments', 20)->nullable()->comment('comments');

            $table->index('id');
            $table->index('tag_name');
            $table->index('tag_no');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corp_tag_lists');
    }
};
