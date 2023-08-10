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
        Schema::table('users', function (Blueprint $table) {
            $table->string('marketing_channel')->after('is_verified')->comment('How did you hear about us?')->nullable();
            $table->string('marketing_source')->after('marketing_channel')->comment('Where did you hear about us?')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
           $table->dropColumn('marketing_channel');
           $table->dropColumn('marketing_source');
        });
    }
};
