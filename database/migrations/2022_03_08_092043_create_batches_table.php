<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('batch_code');
            $table->integer('accept_status')->default(0);
            $table->unsignedBigInteger('storage_id')->nullable();
            $table->foreign('storage_id', 'storage_id_52760127')->references('id')->on('storages')->constrained()
            ->onUpdate('cascade');
            $table->unsignedBigInteger('tl_id_accept')->nullable();
            $table->foreign('tl_id_accept', 'tl_id_accept_52760127')->references('id')->on('users')->constrained()
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
        Schema::dropIfExists('batches');
    }
}
