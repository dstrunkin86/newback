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
        Schema::create('artists', function (Blueprint $table) {
            $table->id();

            $table->enum('source',['arthall','synergy','old_arthall'])->default('arthall');
            $table->enum('status',['new','accepted','rejected'])->default('new');
            $table->text('status_comment')->nullable();

            $table->json('fio');
            $table->string('alias')->nullable();

            $table->string('email')->nullable();
            $table->string('vk')->nullable();
            $table->string('telegram')->nullable();
            $table->string('phone')->nullable();

            $table->string('city')->nullable();
            $table->string('country',2)->default('ru');

            $table->json('creative_concept')->nullable();
            $table->json('education')->nullable();
            $table->json('qualification')->nullable();
            $table->json('exhibitions')->nullable();
            $table->json('publications')->nullable();

            $table->foreignId('user_id')->index()->nullable();

            $table->json('tech_info')->nullable();
            $table->unsignedBigInteger('external_id')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};
