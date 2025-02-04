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
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->nullable(false);
            $table->string('description', 255)->nullable();
            $table->string('logo', 100)->nullable();
            $table->string('phone', 11)->nullable();
            $table->string('annexed', 11)->nullable();
            $table->string('code_ue', 11)->nullable();
            $table->string('goal')->nullable();
            $table->string('code_office', 11)->nullable();
            $table->integer('father_id')->nullable();
            $table->unsignedBigInteger('institution_id')->default(1);
            $table->foreign('institution_id')->references('id')->on('institutions');
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
        Schema::dropIfExists('offices');
    }
};
