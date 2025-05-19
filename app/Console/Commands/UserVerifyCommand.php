<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UserVerifyCommand extends Command
{
    protected $signature = 'user:verify {email}';

    protected $description = 'Command description';

    public function handle()
    {
        $email = $this->argument('email');
        // активувати по емайлу
        $this->info('Hello World '.$this->argument('email'));
    }
}
