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
        Schema::table('cash_register_details', function (Blueprint $table) {
            $table->string('expense_description', 255)->change();
        });

        Schema::table('order_register_details', function (Blueprint $table) {
            $table->string('expense_description', 255)->change();
        });

        Schema::table('viatic_register_details', function (Blueprint $table) {
            $table->string('expense_description', 255)->change();
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
    }
};
