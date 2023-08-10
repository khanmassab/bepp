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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('post_code')->nullable();
            $table->string('phon_number')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->integer('intitil_permation')->nullable();
            $table->integer('punctuality')->nullable();
            $table->integer('cleanliness')->nullable();
            $table->integer('quality')->nullable();
            $table->integer('value')->nullable();
            $table->integer('overall_rating')->nullable();
            $table->double('review_count')->nullable();
            $table->boolean('is_friend')->default(0);
            $table->string('work_image')->nullable();
            $table->longText('comment')->nullable();
            $table->bigInteger('booking_id')->unsigned();
            $table->foreign('booking_id')->references('id')->on('book_providers')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('review_type',['review','missed_appointment','complaint'])->default('review');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
        });

        Schema::dropIfExists('reviews');
    }
};
