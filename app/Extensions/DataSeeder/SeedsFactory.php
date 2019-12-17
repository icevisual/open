<?php
namespace App\Extensions\DataSeeder;

interface SeedsFactory
{

    public function newSeed();

    public function destoryAllSeed(array $seeds);
}