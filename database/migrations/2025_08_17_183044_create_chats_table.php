<?php
// database/migrations/2024_01_01_000006_create_chats_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('item_id')->nullable()->constrained()->onDelete('set null'); // context item
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            
            // Index untuk query chat
            $table->index(['sender_id', 'receiver_id', 'created_at']);
            $table->index(['receiver_id', 'is_read']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('chats');
    }
};