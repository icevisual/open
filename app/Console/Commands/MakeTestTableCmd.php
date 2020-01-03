<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;

class MakeTestTableCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:db';

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
        file_put_contents(storage_path("logs/laravel.log"),"");
        \Schema::dropIfExists('test_cur');
        \Schema::create('test_cur', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $r = `ab -n 100 -c 100 http://smell.open.com/api/t1?name=1`;
        echo $r;

        $table_data = \DB::table("test_cur")->select (\DB::raw("min(id),max(id),count(*)"))->get();
        dump($table_data->toArray());
        return;
    }
}
