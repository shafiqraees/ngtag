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
        Schema::create('admin_portals', function (Blueprint $table) {
            $table->id();
            $table->string('username', 20)->nullable();
            $table->string('user_password', 10)->nullable();
            $table->string('admin_role', 10)->nullable()->comment('1=Admin, 2=Marketing, 3=CS team, 4=LI, 5=MIS');
            $table->dateTime('created_date')->default(now());
            $table->tinyInteger('status')->default(1)->comment('1=active, 0=suspend');
            $table->timestamps();

            $table->index('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_portals');
    }
};
