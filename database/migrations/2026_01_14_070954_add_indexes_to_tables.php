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
            $table->index('location');
            $table->index('space_type');
            $table->index('capacity');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->index('date');
            $table->index(['hall_id', 'date']); // Composite index for availability checks
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('halls', function (Blueprint $table) {
            $table->dropIndex(['location']);
            $table->dropIndex(['space_type']);
            $table->dropIndex(['capacity']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['hall_id']); // Drop foreign key first
            $table->dropIndex(['date']);
            $table->dropIndex(['hall_id', 'date']);
            $table->foreign('hall_id')->references('id')->on('halls')->onDelete('cascade'); // Restore FK if needed or just leave it for the main migration to re-create? 
            // Wait, migrate:refresh rolls back ALL migrations in reverse order.
            // create_bookings_table adds the FK.
            // add_indexes_to_tables adds the index.
            // Rolling back add_indexes_to_tables:
            // It tries to drop the index. But if the index is used by the FK created in create_bookings_table?
            // The FK uses the index on `hall_id`. 
            // The index I added is composite `hall_id, date`.
            // MySQL might be using the composite index for the FK check.
        });
    }
};
