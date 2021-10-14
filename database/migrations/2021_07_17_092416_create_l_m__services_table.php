<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLMServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('l_m__services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('cat_id');
            $table->string('price');
            $table->string('address');
            $table->string('status')->default(0);
            $table->string('price_off',50)->default(0);
            $table->string('price_traffic',50)->default(0);
            $table->string('price_part',50)->default(0);
            $table->string('day');
            $table->string('date');
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
        Schema::dropIfExists('l_m__services');
    }
}
