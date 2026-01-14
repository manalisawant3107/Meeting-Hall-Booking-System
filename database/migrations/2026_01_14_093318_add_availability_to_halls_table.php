<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('halls', function (Blueprint $table) {
            $table->time('start_time')->default('09:00:00');
            $table->time('end_time')->default('18:00:00');
            $table->json('available_days')->nullable(); // Stores ['Monday', 'Tuesday', ...]
        });
    }

    public function down()
    {
        Schema::table('halls', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time', 'available_days']);
        });
    }
};
