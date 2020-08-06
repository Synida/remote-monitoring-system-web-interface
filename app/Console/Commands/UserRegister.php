<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

/**
 * Class UserRegister
 * @package App\Console\Commands
 */
class UserRegister extends Command
{
    /**
     * @const int
     */
    const MIN_PASSWORD_LENGTH = 8;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:register {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registers a user to the database';

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
        $email = $this->argument('email');

        // Validates the the email.
        if (!$this->isEmailValid($email)) {
            return 1;
        }

        $this->info('Password: ');
        $password = readline();
        // Validates the password.
        if (!$this->isPasswordValid($password)) {
            return 1;
        }

        // Creates a user in the database.
        $this->createUser($email, $password);

        $this->info("User({$email}) successfully registered!");

        return 0;
    }

    /**
     * Creates a user in the database.
     *
     * @param string $email
     * @param string $password
     * @return void
     * @author Synida Pry
     */
    protected function createUser($email, $password): void
    {
        $params = [
            'email' => $email,
            'password' => bcrypt($password)
        ];

        User::create($params);
    }

    /**
     * Validates the the email.
     *
     * @param string $email
     * @return bool
     * @author Synida Pry
     */
    protected function isEmailValid($email): bool
    {
        $regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
        if (!preg_match($regex, $email)) {
            $this->error('Email address is not valid');
            return 0;
        }

        return 1;
    }

    /**
     * Validates the password.
     *
     * @param string $password
     * @return bool
     * @author Synida Pry
     */
    protected function isPasswordValid($password): bool
    {
        if (strlen($password) < static::MIN_PASSWORD_LENGTH) {
            $this->error('Password should be at least ' . static::MIN_PASSWORD_LENGTH . ' characters long');
            return 0;
        }

        return 1;
    }
}
