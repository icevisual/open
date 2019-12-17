<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\Open\IndexController;
use App\Exceptions\ServiceException;
use App\Extensions\DataSeeder\Seeder;
use App\Extensions\DataSeeder\Seeds\EmailSeeds;
use App\Extensions\DataSeeder\Seeds\DbEmailSeeds;
use App\Extensions\DataSeeder\Seeds\DbPhoneSeeds;
use App\Extensions\DataSeeder\Seeds\App\Extensions\DataSeeder\Seeds;

class ExampleTest extends TestRoutes
{
    public static function setUpBeforeClass()
    {
        defined('DS') or define('DS', DIRECTORY_SEPARATOR);
        fwrite(STDOUT, __METHOD__ . "\n");
    }

    public function clearAccount($email)
    {
        \App\Models\User\Account::where('email', $email)->delete();
    }


    /**
     * Generate Email (diff used clear ),and after all destory
     */
    public function emailProvider($command = 'diff')
    {
        static $emailSeeder = null;
        if (! $emailSeeder) {
            $emailSeeder = new Seeder(new EmailSeeds());
        }
        if ('diff' == $command) {
            return $emailSeeder->seedNew();
        } else 
            if ('used') {
                return $emailSeeder->seedOld();
            } else 
                if ('clear') {
                    return $emailSeeder->destorySeeds();
                }
    }


    public function testExample()
    {
        $emailSeeder = new Seeder(new DbPhoneSeeds(2));
        $emailSeeder->addSeed('15487458445');
        dump($emailSeeder->seedNew());
        dump($emailSeeder->seedNew());
        dump($emailSeeder->seedOld());
        dump($emailSeeder->seedOld());
        dump($emailSeeder->seedOld());
        dump($emailSeeder->seedOld());
        dump($emailSeeder->seedOld());
    }
    
    
    
    
    
}
