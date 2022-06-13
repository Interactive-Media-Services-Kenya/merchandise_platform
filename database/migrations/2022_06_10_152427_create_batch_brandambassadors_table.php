<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchBrandambassadorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_brandambassadors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_ambassador_id')->nullable();
            $table->string('batch_code')->nullable();
            $table->integer('accept_status')->default(0);
            $table->integer('reject_status')->default(0);
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
        Schema::dropIfExists('batch_brandambassadors');
    }
}
