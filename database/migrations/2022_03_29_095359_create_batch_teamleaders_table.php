<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchTeamleadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_teamleaders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('team_leader_id')->nullable();
            $table->foreign('team_leader_id', 'team_leader_id_fk_5206527')->references('id')->on('users')->constrained()
                ->onUpdate('cascade');
                $table->unsignedBigInteger('batch_id')->nullable();
            $table->foreign('batch_id', 'batch_id_fk_99096527')->references('id')->on('users')->constrained()
                ->onUpdate('cascade');
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
        Schema::dropIfExists('batch_teamleaders');
    }
}
