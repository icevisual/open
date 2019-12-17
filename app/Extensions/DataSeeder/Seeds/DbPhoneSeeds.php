<?php
namespace App\Extensions\DataSeeder\Seeds;

use App\Extensions\DataSeeder\SeedsFactory;

class DbPhoneSeeds implements SeedsFactory
{

    protected $maxId = 0;

    protected $limit = 10;

    public function __construct($limit = 10)
    {
        $this->limit = $limit;
    }

    protected function updateMax($max)
    {
        $this->maxId = $max;
    }

    protected function getData()
    {
        $ret = [];
        $data = \App\Models\User\Account::select([
            'phone',
            'id'
        ])->where('id', '>', $this->maxId)
            ->whereNotNull('phone')
            ->orderBy('id', 'asc')
            ->limit($this->limit)
            ->get()
            ->toArray();
        if ($data) {
            
            foreach ($data as $v) {
                $ret[] = $v['phone'];
            }
            $this->updateMax($v['id']);
        }
        return $ret;
    }

    public function newSeed()
    {
        static $_cache = [];
        if (empty($_cache)) {
            $_cache = $this->getData();
            if (empty($_cache)) {
                return false;
            }
        }
        return array_shift($_cache);
    }

    public function destoryAllSeed(array $seeds)
    {}
}