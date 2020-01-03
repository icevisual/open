<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendAnotherEmailCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send
                        {user : The ID of the user}
                        {--queue= : Whether the job should be queued}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $headers = ['Name', 'Email'];

        $users = \App\Models\User\Account::all(['account', 'email'])->toArray();

        $this->table($headers, $users);

        $bar = $this->output->createProgressBar(count($users));

        foreach ($users as $user) {
            // $this->performTask($user);
            sleep(1);
            $bar->advance();
        }

        $bar->finish();


        $this->error('Something went wrong!');
        $this->line('Display this on the screen');
        $this->info('Display this on the screen');
        $this->question('Display this on the screen');
      //  $name = $this->ask('What is your name?');
        $name = $this->choice('What is your name?', ['Taylor', 'Dayle'], 'Taylor');
        $name = $this->anticipate('What is your name?', ['Taylor', 'Dayle']);
        if($this->confirm("ffff?",true))
        {
            $this->comment("you confirmed");
        }
        $var = $this->arguments();
        $var1 = $this->options();
        dump($var);
        dump($var1);
        //
        $this->comment("this is command {$this->name}");
    }
}
