<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');

        // Optional fields
        $table->string('department')->nullable();
        $table->string('designation')->nullable();
        $table->date('joining_date')->nullable();
        $table->string('phone')->nullable();
        $table->enum('gender', ['male', 'female', 'other'])->nullable();

        $table->rememberToken(); // for "remember me" functionality
        $table->timestamps();    // created_at and updated_at
    });
}
};
