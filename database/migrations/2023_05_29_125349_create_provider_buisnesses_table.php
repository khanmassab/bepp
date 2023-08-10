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
        Schema::create('provider_buisnesses', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->enum('buisness_type',['trader','garage'])->dafault('trader');
            $table->string('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('loc_name')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('website_link')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    { Schema::table('provider_buisnesses', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
    });
        Schema::dropIfExists('provider_buisnesses');
    }
};
