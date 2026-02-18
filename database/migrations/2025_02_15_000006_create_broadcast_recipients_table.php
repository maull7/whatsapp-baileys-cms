<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('broadcast_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broadcast_id')->constrained()->cascadeOnDelete();
            $table->string('phone');
            $table->string('name')->nullable();
            $table->string('status')->default('pending');
            $table->text('error')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('broadcast_recipients');
    }
};
