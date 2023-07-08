<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100)->nullable()->comment('First name of the user');
            $table->string('last_name', 100)->nullable()->comment('Last name of the user');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable()->comment('Email verified at');
            $table->string('password_hash');
            $table->string('password_reset_token')->nullable();
            $table->date('birth_date')->nullable()->comment('Birth date of the user');
            $table->integer('status')->default(0)->comment('Status of the user');
            $table->string('language', 10)->nullable()->comment('Language of the user');
            $table->string('avatar')->nullable()->comment('Avatar of the user');
            $table->integer('gender')->nullable()->comment('Gender of the user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
