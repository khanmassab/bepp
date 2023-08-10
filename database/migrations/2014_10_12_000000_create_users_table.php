<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('post_code')->nullable();
            $table->string('phone_number')->nullable();
            
            $table->date('call_date')->nullable();
            $table->time('call_from_time')->nullable();
            $table->time('call_to_time')->nullable();
            $table->string('member_ship_number')->nullable();
            $table->boolean('is_verified')->default(0);
            $table->rememberToken();
            $table->timestamp('expires_at')->nullable();
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
