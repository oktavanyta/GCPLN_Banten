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
        Schema::create('groundchecks', function (Blueprint $table) {
            $table->id();
            $table->string('jenis'); // prabayar / pascabayar
            $table->foreignId('ulp_id')->constrained('ulps')->cascadeOnDelete();
            $table->integer('open')->default(0);
            $table->integer('submitted')->default(0);
            $table->integer('rejected')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groundchecks');
    }
};
