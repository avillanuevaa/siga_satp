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
        Schema::table('order_registers', function (Blueprint $table) {
            $table->decimal('amount_to_pay', 10, 2)->nullable(true);
            $table->decimal('amount_to_returned', 10, 2)->nullable(true);
            $table->string('surrender_report')->nullable(true);
            $table->tinyInteger('closed')->default(0)->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_registers', function (Blueprint $table) {
            $table->dropColumn('amount_to_pay');
            $table->dropColumn('amount_to_returned');
            $table->dropColumn('surrender_report');
            $table->dropColumn('closed');
        });
    }
};
