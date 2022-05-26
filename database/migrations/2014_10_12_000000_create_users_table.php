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
            $table->string('country');
            $table->string('nationality');
            $table->string('district');
            $table->string('Town');
            $table->string('postal');
            $table->string('phone_1')->default('');
            $table->string('phone_2')->default('');
            $table->enum('gender', ['Male', 'Female']);
            $table->date('date_of_birth')->nullable();
            $table->string('profileImage')->default('default.jpg');
            $table->string('religion')->default('');
            $table->string('marital_status')->default('');
            $table->string('spouse_name')->default('');
            $table->string('spouse_contact')->default('');
            $table->enum('disability', ['Yes', 'No']);
            $table->string('nature_of_disability')->default('');
            $table->string('role')->default('Super User');
            $table->string('father_name')->default('');
            $table->string('father_contact')->default('');
            $table->string('mother_name')->default('');
            $table->string('mother_contact')->default('');
            $table->timestamp('email_verified_at')->nullable();
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
