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
        Schema::table('order_register_detail_warehouses', function (Blueprint $table) {
            //
            $table->string('observation')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_register_detail_warehouses', function (Blueprint $table) {
            //
            $table->dropColumn('observation');
        });
    }
};
