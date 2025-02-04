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
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('label', 50);
            $table->string('description', 100);
            $table->string('icon', 50)->default("");
            $table->string('link', 50);
            $table->integer('expanded');
            $table->integer('parentId');
            $table->integer('isTitle');
            $table->integer('level');
            $table->integer('order');
            $table->string('badge', 50);
            $table->integer('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu');
    }
};
