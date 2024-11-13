<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    const ROLES = [
        'regular_user',
        'artist',
        'moderator',
        'admin'
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        echo "\r\n";

        foreach (self::ROLES as $role) {
            $created = Role::create(['name' => $role]);
            echo "Создана роль: ".$created->name."\r\n";
        }

        $user = User::where('name','admin')->first();

        $user->assignRole('admin');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        echo "\r\n";

        $user = User::where('name','admin')->first();

        $user->removeRole('admin');

        foreach (self::ROLES as $role) {
            $found = Role::findByName($role);
            if ($found) {

                echo "Удалена роль: ".$found->name."\r\n";
                $found->delete();
            }
        }
    }
};
