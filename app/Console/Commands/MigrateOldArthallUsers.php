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
        $num_tokens = OldArthallPersonalAccessToken::where('last_used_at', '>', '2023-01-01')->count();
        $this->line($num_tokens  . ' users to be created');
        $tokens = OldArthallPersonalAccessToken::where('last_used_at', '>', '2023-01-01')->orderBy('id','desc')->get();
        $i = 0;
        foreach ($tokens as $token) {
            $user = OldArthallUser::find($token->tokenable_id);
            $user = User::create($user->toArray());
            $user->syncRoles(['regular_user']);

            $token->tokenable_id = $user->id;
            $token->tokenable_type = get_class($user);
            $token = PersonalAccessToken::create($token->toArray());
            $i++;
            if ($i == 1000) {
                $this->line('1000 tokens done');
                $i = 0;
            }
        }

    }
}
