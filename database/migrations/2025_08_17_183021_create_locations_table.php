<?php
// database/migrations/2024_01_01_000003_create_locations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->text('address');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('city');
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            $table->timestamps();
            
            // Index untuk pencarian geolokasi
            $table->index(['latitude', 'longitude']);
            $table->index('city');
        });
    }

    public function down()
    {
        Schema::dropIfExists('locations');
    }
};