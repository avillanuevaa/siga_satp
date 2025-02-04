<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('order_registers', function (Blueprint $table) {
            $table->dropColumn('approved_amount');
            $table->dropColumn('authorization_date');
            $table->dropColumn('authorization_detail');
            $table->dropColumn('reason');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('order_registers', function (Blueprint $table) {
            $table->date('authorization_date')->nullable(true);
            $table->string('authorization_detail')->nullable(false);
            $table->string('reason')->nullable(false);
        });
    }
};
