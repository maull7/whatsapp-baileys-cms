<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('phone');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();

            $table->unique(['client_id', 'phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
