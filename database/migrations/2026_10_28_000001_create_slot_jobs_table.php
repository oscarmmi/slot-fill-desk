<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slot_jobs', function (Blueprint $table) {
            $table->id();
            $table->text('source_text');
            $table->string('status')->default('complete');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slot_jobs');
    }
};
