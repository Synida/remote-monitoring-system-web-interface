<?php

namespace App\Console\Commands;

use App\User;
use Exception;
use Illuminate\Console\Command;

/**
 * Class UserDelete
 * @package App\Console\Commands
 */
class UserDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:delete {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes a user from the database';

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
     * @throws Exception
     */
    public function handle()
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user instanceof User) {
            $this->error("User({$email}) not found in the database.");
            return 1;
        }

        $user->delete();

        $this->info('User deleted successfully!');

        return 0;
    }
}
