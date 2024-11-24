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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->enum('language',['ru','en','ar','cn'])->default('ru');
            $table->text('text');
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('publication_date');
            $table->boolean('is_published')->default(false);
            $table->string('link')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
