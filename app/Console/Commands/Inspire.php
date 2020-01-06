<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Models\User\DeveloperDevBind;

class Inspire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inspire:mq';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display an inspiring quote';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        dump(resolve(\App\Extensions\Lock\Locker::class));

//
//        $result = \DB::select("SELECT GET_LOCK('key',10) AS MyLOCK ");
//
//        dump($result[0]->MyLOCK);

        exit;
        
        $opts = [
            CURLOPT_HTTPHEADER => [
                'Authorization:' ."Basic YWRtaW46cHVibGlj"
            ]
        ];
        $res =  curl_get('http://121.41.33.141:18083/api/clients',[],true,$opts);
        dd($res);
//         url : 'http://121.41.33.141:18083/api/clients',
//         headers: {
//             Authorization: "Basic YWRtaW46cHVibGlj"
//         $phone = '18767135775';
//         $code = '987654';
//         $res = \App\Services\Sms\SmsServices::sendBigFish($phone,[
//             'code' => $code,
//             'n' => '10'
//         ]);
        
        
//         333
        
        
        $dd =  DeveloperDevBind::listUserBindedDevices(333);
        dd($dd);
//         edump($res);
        ;
        $r = \App\Models\Open\Device::all();
        
        edump($r->toArray());
        
        
        edump((request()->isSecure() ? 'https://' : 'http://').request()->getHost());
        
        $EmailSender = new \App\Services\Email\EmailSender();
        // 1012149817
        $param = [
            'username' => '779662959@qq.com',
            'link' => 'asdad',
        ];
        
        
//         $param = [
//             'email' => '779662959@qq.com',
//             'validateUrl' => 'asdad',
//         ];
        
//             $ret = $EmailSender->sendEmail('779662959@qq.com',  \App\Services\Email\EmailSender::EMAIL_REGISTER, $param);
        
        $ret = $EmailSender->sendEmail('779662959@qq.com',  \App\Services\Email\EmailSender::EMAIL_PASSWRD_RESET, $param);
        dump($ret);
        
        
        $this->comment(PHP_EOL.Inspiring::quote().PHP_EOL);
    }
}
