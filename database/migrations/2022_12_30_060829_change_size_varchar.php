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
        Schema::table('people', function (Blueprint $table) {
            $table->string('name', 255)->change();
            $table->string('lastname', 255)->change();
            $table->string('address', 255)->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 255)->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 255)->change();
        });

        Schema::table('financial_classifiers', function (Blueprint $table) {
            $table->string('name', 255)->change();
        });

        Schema::table('institutions', function (Blueprint $table) {
            $table->string('name', 255)->change();
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->string('reference_document', 255)->change();
            $table->string('purpose', 255)->change();
            $table->string('justification', 255)->change();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->string('name', 255)->change();
            $table->string('description', 255)->change();
        });

        Schema::table('settlements', function (Blueprint $table) {
            $table->string('budget_certificate', 255)->change();
            $table->string('reason', 255)->change();
            $table->string('authorization_detail', 255)->change();
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
