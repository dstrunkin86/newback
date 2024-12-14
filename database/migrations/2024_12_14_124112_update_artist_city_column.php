<?php

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
        DB::statement('UPDATE artists SET city = NULL where city = "-"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('UPDATE artists SET city = "-" where city IS NULL');
    }
};