<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('libelle')->nullable();
            $table->dateTime('date')->nullable();
            $table->integer('montant')->default(0);
            $table->integer('balance')->default(0);
            $table->foreignId('wallet_id')->index()->constrained('wallets')->cascadeOnDelete();
            $table->foreignId('reservation_id')->nullable()->index()->constrained('reservations')->cascadeOnDelete();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
