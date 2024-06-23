<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DataHelpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:help';

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
        $users = User::query()->where('id', '>', 269)->get();

        foreach ($users as $user) {
            $user->clientDetails()->create();
        }
    }
}
