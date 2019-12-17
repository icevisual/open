<?php
namespace App\Extensions\DataSeeder\Seeds;

use App\Extensions\DataSeeder\SeedsFactory;

class PhoneSeeds implements SeedsFactory
{

    /**
     * Generate random phone number
     *
     * @return string
     */
    public function randomPhone()
    {
        // /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}
        $data = [
            '13',
            '15',
            '17',
            '18',
            '14'
        ];
    
        $h = $data[random_int(0, 4)];
        $t = random_int(100000000, 999999999);
        return $h . $t;
    }
    
    public function newSeed()
    {
        return $this->randomPhone();
    }

    public function destoryAllSeed(array $seeds)
    {}
}