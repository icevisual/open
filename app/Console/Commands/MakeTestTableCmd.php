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
    protected $signature = 'test:db {n=10000} {c=100}';

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
//
       // edump(date("Y-m-d H:m:s"));
//        $a = time() - strtotime('2014-09-18');
//        edump($a/86400/30/12);
//        $b = strtotime('2017-11-11');
        $this->info("Clear `logs/laravel.log`",1);

        file_put_contents(storage_path("logs/laravel.log"),"");

        $this->info("Recreate Table `test_cur`");
        \Schema::dropIfExists('test_cur');
        \Schema::create('test_cur', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('account')->default(0);
            $table->double('tm')->default(0.0);
            $table->timestamps();
        });
        //

        \DB::statement('alter table op_test_cur modify updated_at timestamp default null on update CURRENT_TIMESTAMP;');

        $this->info("Clear Redis");

        \DB::insert("insert into op_test_cur (`id`,`name`,`created_at`)values(?,?,?)",[
             100,"name", date("Y-m-d H:m:s")
        ]);

        \LRedis::DEL("LastID");
        $this->info("Run ab");

        $n = $this->argument('n');
        $c = $this->argument('c');

        $r = `ab -n $n -c $c http://smell.open.com/api/t1?name=1`;
        $arr = explode("\n",$r);
        $arr = array_slice($arr,14);
        $this->info("Summary");
        foreach ($arr as $v)
            echo $v.PHP_EOL;

        $this->info("Static Data");
        $table_data = \DB::table("test_cur")->select (\DB::raw("min(id),max(id),count(*)"))->get();
        dump($table_data->toArray());
        return;
    }
}
