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
        Schema::create('artworks', function (Blueprint $table) {
            $table->id();

            $table->enum('status',['new','accepted','rejected'])->default('new');
            $table->text('status_comment')->nullable();

            $table->index('status');

            $table->json('title');
            $table->json('description');
            $table->smallInteger('year')->nullable();
            $table->string('location')->nullable();

            $table->foreignId('artist_id')->index();

            $table->float('width')->nullable();
            $table->float('height')->nullable();
            $table->float('depth')->nullable();
            $table->float('weight')->nullable();

            $table->boolean('in_sale')->default(false);
            $table->float('price')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artworks');
    }
};
