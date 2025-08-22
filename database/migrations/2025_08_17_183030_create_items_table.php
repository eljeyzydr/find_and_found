<?php
// database/migrations/2024_01_01_000004_create_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['lost', 'found']); // lost = hilang, found = ditemukan
            $table->json('photos')->nullable(); // array foto
            $table->date('event_date'); // tanggal kehilangan/penemuan
            $table->boolean('is_active')->default(true);
            $table->boolean('is_resolved')->default(false); // sudah ketemu pemilik
            $table->timestamp('resolved_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->timestamps();
            
            // Index untuk pencarian
            $table->index(['status', 'is_active']);
            $table->index('event_date');
            $table->index('is_resolved');
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
};