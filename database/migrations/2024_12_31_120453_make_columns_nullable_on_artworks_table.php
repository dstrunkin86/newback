<?php

use App\Models\Artwork;
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
        Schema::table('artworks', function (Blueprint $table) {
            $table->json('title')->nullable()->change();
            $table->json('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Artwork::whereNull('description')->update([
            'description' => []
        ]);

        Artwork::whereNull('title')->update([
            'title' => []
        ]);

        Schema::table('artworks', function (Blueprint $table) {
            $table->json('title')->change();
            $table->json('description')->change();
        });
    }
};
