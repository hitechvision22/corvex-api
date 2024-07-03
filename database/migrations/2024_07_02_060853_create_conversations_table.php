<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user1_id')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('user2_id')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('sender_id')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('reservation_id')->index()->constrained('reservations')->cascadeOnDelete();
            $table->foreignId('trajet_id')->index()->constrained('trajets')->cascadeOnDelete();
            $table->Text('last_message');
            $table->timestamp('read_at')->nullable();
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
        Schema::dropIfExists('conversations');
    }
}
