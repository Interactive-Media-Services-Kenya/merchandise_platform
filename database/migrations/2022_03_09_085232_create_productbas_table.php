<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductbasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productbas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('batch_id')->default(1);
            $table->foreign('batch_id', 'batch_fk_53227')->references('id')->on('batches')->constrained()
                ->onUpdate('cascade');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->foreign('assigned_to', 'assigned_to_fk_52fd2327')->references('id')->on('users')->constrained()
                ->onUpdate('cascade');
            $table->unsignedBigInteger('product_id')->nullable()->default(1);
            $table->foreign('product_id', 'product_fk_53227')->references('id')->on('products')->constrained()
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
        Schema::dropIfExists('productbas');
    }
}
