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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id');
            $table->date('date')->nullable();
            $table->string('name');
            $table->string('short_desc');
            $table->string('desc');
            $table->string('preview_image')->nullable();
            $table->string('full_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
