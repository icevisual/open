<?php
namespace App\Extensions\DataSeeder\Seeds;

use App\Extensions\DataSeeder\SeedsFactory;

class EmailSeeds implements SeedsFactory
{

    public function newSeed()
    {
        $domain = [
            'renrenfenqi',
            'qq',
            'gamil',
            '163',
            'hotmail'
        ];
        
        $suffix = [
            'com',
            'cn'
        ];
        
        $seg = [
            str_random(8),
            $domain[array_rand($domain)],
            $suffix[array_rand($suffix)]
        ];
        return sprintf('%s@%s.%s', $seg[0], $seg[1], $seg[2]);
    }

    public function destoryAllSeed(array $seeds)
    {
        \App\Models\User\Account::whereIn('email', array_keys($seeds))->delete();
    }
}