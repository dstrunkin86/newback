<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'dstrunkin@gmail.com',
            'password' => Hash::make('22061941')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $user = User::where('name','admin')->delete();
    }
};
