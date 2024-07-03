<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRencontresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rencontres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_client');
            $table->unsignedBigInteger('id_chauffeur');
            $table->string('status')->default('non-meet');
            $table->boolean('checkChauffeur')->nullable();
            $table->boolean('checkClient')->nullable();
            $table->foreign('id_client')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_chauffeur')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('rencontres');
    }
}
