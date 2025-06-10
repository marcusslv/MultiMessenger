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
        Schema::create('message_failures', function (Blueprint $table) {
            $table->id();
            $table->string('to');
            $table->text('message')->nullable();
            $table->string('driver');
            $table->text('error')->nullable();
            $table->json('options')->nullable();
            $table->enum('status', ['failed'])->default('failed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_failures');
    }
};
