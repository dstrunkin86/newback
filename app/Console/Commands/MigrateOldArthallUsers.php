<?php

namespace App\Console\Commands;

use App\Models\OldArthall\PersonalAccessToken as OldArthallPersonalAccessToken;
use App\Models\OldArthall\User as OldArthallUser;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateOldArthallUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-old-arthall-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tokens = OldArthallPersonalAccessToken::where('last_used_at', '>', '2023-01-01')->chunk(10000, function ($tokens) {
            $this->line(count($tokens) . ' users to be created');
            foreach ($tokens as $token) {
                //DB::connection('mysql_old_arthall')->enableQueryLog();
                $user = OldArthallUser::find($token->tokenable_id);
                //$log = DB::connection('mysql_old_arthall')->getQueryLog();
                //dd($log);
                $user = User::create($user->toArray());

                $user->syncRoles(['regular_user']);

                $token->tokenable_id = $user->id;
                $token->tokenable_type = get_class($user);

                $token = PersonalAccessToken::create($token->toArray());
            }
            $this->line('10000 tokens done');
        });
    }
}
