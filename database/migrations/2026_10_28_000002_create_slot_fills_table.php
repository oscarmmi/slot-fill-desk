<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slot_fills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slot_job_id')->unique()->constrained()->cascadeOnDelete();
            $table->date('date')->nullable();
            $table->string('window')->nullable();
            $table->string('city')->nullable();
            $table->json('missing');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slot_fills');
    }
};
