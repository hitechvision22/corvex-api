<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrajetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trajets', function (Blueprint $table) {
            $table->id();
            $table->string('ville_depart');
            $table->string('point_rencontre');
            $table->string('ville_destination');
            $table->string('point_destination');
            $table->string('date_depart');
            $table->string('heure_depart');
            $table->double('prix');
            $table->unsignedBigInteger('Nombre_de_place');
            $table->unsignedBigInteger('nombre_de_place_disponible');
            $table->string('Mode_de_paiement')->default('om/momo');
            $table->string('etat')->default('Deactif');
            $table->string('status')->default('initie');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->engine = "InnoDB";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trajets');
    }
}
