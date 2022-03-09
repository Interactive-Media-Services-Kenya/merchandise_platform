<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_code');
            $table->unsignedBigInteger('category_id')->default(1);
            $table->foreign('category_id', 'category_fk_5358227')->references('id')->on('categories')->constrained()
                ->onUpdate('cascade');
            $table->unsignedBigInteger('user_id')->default(1);
            $table->foreign('user_id', 'user_fk_5227')->references('id')->on('users')->constrained()
                ->onUpdate('cascade');
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->foreign('batch_id', 'batch_fk_5358227')->references('id')->on('batches')->constrained()
                ->onUpdate('cascade');
            $table->unsignedBigInteger('client_id')->default(1);
            $table->foreign('client_id', 'client_fk_5358227')->references('id')->on('clients')->constrained()
                ->onUpdate('cascade');
            $table->unsignedBigInteger('assigned_to')->default(1);
            $table->foreign('assigned_to', 'assigned_to_5358227')->references('id')->on('users')->constrained()
                ->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}