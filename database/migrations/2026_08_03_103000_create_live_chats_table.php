<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_chats', function (Blueprint $table) {
            $table->id();
            $table->string('session_token', 64)->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->enum('status', ['active', 'ended'])->default('active');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });

        Schema::create('live_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_chat_id')->constrained('live_chats')->onDelete('cascade');
            $table->enum('sender_type', ['customer', 'admin', 'system']);
            $table->string('sender_name')->nullable();
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_chat_messages');
        Schema::dropIfExists('live_chats');
    }
};
