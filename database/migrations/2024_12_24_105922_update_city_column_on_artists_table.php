<?php

use App\Models\Artist;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $artists = DB::select('SELECT id,city FROM artists where city is not null');
        $replace = [];
        foreach ($artists as $artist) {
            $replace[] = [
                "id" => $artist->id,
                "city" => $artist->city
            ];
        }

        DB::statement('UPDATE artists SET city = NULL where id > 0');

        Schema::table('artists', function (Blueprint $table) {
            $table->json('city')->nullable()->change();
        });

        foreach ($replace as $replacement) {
            $artist = Artist::findOrFail($replacement['id']);
            $artist->city = (object) [
                "city" => $replacement['city'],
                "fias" => "",
                "lat" => "",
                "long" => ""
            ];
            $artist->save();
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->string('city')->nullable()->change();
        });
    }
};
