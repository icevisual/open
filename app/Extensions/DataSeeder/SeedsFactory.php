<?php
namespace App\Extensions\DataSeeder;

interface SeedsFactory
{

    public function newSeed();

    public function destroyAllSeed(array $seeds);
}