<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssueProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issue_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id', 'product_id_fk_52xd2327')->references('id')->on('products')->constrained()
                ->onUpdate('cascade');
            $table->unsignedBigInteger('ba_id')->nullable();
            $table->foreign('ba_id', 'ba_id_fk_52fx2327')->references('id')->on('users')->constrained()
                ->onUpdate('cascade');
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->foreign('batch_id', 'batch_id_fk_5223027')->references('id')->on('batches')->constrained()
                ->onUpdate('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id', 'category_id_fk_5223027')->references('id')->on('categories')->constrained()
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
        Schema::dropIfExists('issue_products');
    }
}
