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
		        $table->string('is_active')->boolean()->default(true);
                $table->string('password');
                $table->string('phone_number');
                $table->enum('gender', ['male', 'female', 'other']);
                $table->string('department');
                $table->enum('role', ['admin', 'user']);
    
                $table->string('email_verification_code')->nullable();
                $table->timestamp('email_verified_at')->nullable();
    
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
