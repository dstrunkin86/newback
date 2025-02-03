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
        // надо убрать location из casts
        $artworks = Artwork::withTrashed()->get();
        foreach ($artworks as $artwork) {
            if (($artwork->location != null)&&(!json_validate($artwork->location))) {
                $artwork->tech_info = (object) ["old_location" => $artwork->location];
                $artwork->location = null;
                $artwork->save();
            }
        }

        Schema::table('artworks', function (Blueprint $table) {
            $table->json('location')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // надо добавить location в casts
        $artworks = Artwork::withTrashed()->get();
        foreach ($artworks as $artwork) {
            $artwork->update([
                'location' => null
            ]);
        }

        Schema::table('artworks', function (Blueprint $table) {
            $table->string('location')->nullable()->change();
        });

        $artworks = Artwork::withTrashed()->get();
        foreach ($artworks as $artwork) {
            if (isset($artwork->tech_info->old_location)) {
                $artwork->update([
                    'location' => $artwork->tech_info->old_location
                ]);
            }

        }
    }
};
