<?php
// database/migrations/2024_01_01_000008_create_notifications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->string('type'); // comment, chat, match, system
            $table->json('data')->nullable(); // data tambahan (item_id, etc)
            $table->boolean('is_read')->default(false);
            $table->boolean('is_sent_email')->default(false);
            $table->timestamps();
            
            $table->index(['user_id', 'is_read', 'created_at']);
            $table->index('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};