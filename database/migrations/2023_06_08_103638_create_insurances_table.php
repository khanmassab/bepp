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
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
           
           $table->string('title')->nullable();
           $table->string('name')->nullable();
           $table->string('phone_number')->nullable();
           $table->string('email')->nullable(); 
           $table->string('post_code')->nullable();
           $table->date('renewal_date')->nullable();
           $table->bigInteger('product_id')->unsigned();
           $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
           $table->text('contact_on')->comment('how you would like us to contact you')->nullable();
           $table->boolean('status')->dafault(0)->comment('agree to be connected');
         
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insurances', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
        Schema::dropIfExists('insurances');
    }
};
