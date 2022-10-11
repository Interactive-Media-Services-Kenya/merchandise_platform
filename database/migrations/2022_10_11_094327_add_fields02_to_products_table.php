<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFields02ToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('issue_products', function (Blueprint $table) {
            $table->string('ip-address')->nullable();
            $table->string('state_name')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('issue_products', function (Blueprint $table) {
            //
        });
    }
}
