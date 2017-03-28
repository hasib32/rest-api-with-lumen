<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('uid', 36)->unique();
            $table->string('firstName', '100')->nullable();
            $table->string('lastName', '100')->nullable();
            $table->string('middleName', '50')->nullable();
            $table->string('username', '50')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('address')->nullable();
            $table->string('zipCode')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('city', '100')->nullable();
            $table->string('state', '100')->nullable();
            $table->string('country', '100')->nullable();
            $table->enum('role', ['BASIC_USER', 'ADMIN_USER'])->default('BASIC_USER');
            $table->tinyInteger('isActive');
            $table->string('profileImage')->nullable();
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
        Schema::dropIfExists('users');
    }
}
