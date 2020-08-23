<?php

namespace App\Console\Commands\User;

use App\User;
use Illuminate\Console\Command;

/**
 * Class UserList
 * @package App\Console\Commands\User
 */
class UserList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists registered user emails in the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::all();

        $this->info('Registered emails in the database:');
        foreach ($users as $user) {
            $this->info($user->email);
        }

        return 0;
    }
}
