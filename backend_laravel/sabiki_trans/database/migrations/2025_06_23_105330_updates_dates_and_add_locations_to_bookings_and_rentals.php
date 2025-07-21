<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('start_date')->nullable()->change();
            $table->timestamp('end_date')->nullable()->change();

            $table->string('pickup_location')->nullable();
            $table->string('return_location')->nullable();
        });

        Schema::table('rentals', function (Blueprint $table) {
            $table->timestamp('pickup_date')->nullable()->change();
            $table->timestamp('return_date')->nullable()->change();

            $table->string('pickup_location')->nullable();
            $table->string('return_location')->nullable();
        });
    }


    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->date('start_date')->change();
            $table->date('end_date')->change();
        });

        Schema::table('rentals', function (Blueprint $table) {
            $table->date('pickup_date')->change();
            $table->date('return_date')->change();

            $table->dropColumn('pickup_location');
            $table->dropColumn('return_location');
        });
    }
};
