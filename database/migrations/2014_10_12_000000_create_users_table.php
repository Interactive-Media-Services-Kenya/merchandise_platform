<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedBigInteger('role_id')->default(4);
            $table->foreign('role_id', 'role_fk_5358227')->references('id')->on('roles')->constrained()
            ->onUpdate('cascade');
            $table->unsignedBigInteger('county_id')->nullable();
            $table->foreign('county_id', 'county_fk_5358227')->references('id')->on('counties')->constrained()
            ->onUpdate('cascade');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id', 'client_id_fk_52760127')->references('id')->on('clients')->constrained()
            ->onUpdate('cascade');
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
