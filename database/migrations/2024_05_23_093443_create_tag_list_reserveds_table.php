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
        Schema::create('reserved_tags', function (Blueprint $table) {
            $table->id()->comment('AUTO_INCREMENT');
            $table->string('tag_name', 15)->nullable()->comment('tag name ABC');
            $table->string('tag_no', 15)->nullable()->comment('tag code 002');
            $table->string('tag_type', 15)->nullable()->comment('VIP, Golden');
            $table->tinyInteger('tag_digits')->nullable()->comment('length of tags 2,3');
            $table->dateTime('created_date')->default(now());
            $table->string('reserve_type', 15)->nullable()->comment('GOV, OFFICIAL');
            $table->string('reserve_by', 15)->nullable()->comment('SYSTEM = ETHIO');
            $table->tinyInteger('status')->nullable()->comment('0=reserve');
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
        Schema::dropIfExists('reserved_tags');
    }
};
