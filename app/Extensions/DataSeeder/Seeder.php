<?php
namespace App\Extensions\DataSeeder;

class Seeder
{

    protected $cache = [];

    /**
     *
     * @var \App\Extensions\DataSeeder\SeedsFactory
     */
    protected $factory;

    public function __construct(SeedsFactory $factory)
    {
        $this->factory = $factory;
    }
    
    
    /**
     * 
     * @param unknown $seedClass
     * @param string $cmd new|old|add
     * @param unknown $extra
     * @return boolean|mixed
     */
    public static function seed($seedClass,$cmd = 'new|old|add',$extra = []){
        static $_cached = [];
        if(!class_exists($seedClass)){
            throw new \Exception('Class '.$seedClass .' Not Found!');
            return false;
        }
        if (! isset($_cached[$seedClass])) {
            $_cached[$seedClass] = new Seeder(new $seedClass());
        }
        
        $cmdMap = [
            'new' => 'seedNew',
            'old' => 'seedOld',
            'add' => 'addSeed',
        ];
        
        if(array_key_exists($cmd, $cmdMap)){
            return call_user_func_array([
                $_cached[$seedClass],
                $cmdMap[$cmd]
            ], $extra);
        }
        return false;
    }

    /**
     * 
     * @param unknown $seed
     * @return string
     */
    public function makeSeedKey($seed){
        if(! (is_array($seed) || is_object($seed))){
            $seed = [$seed];
        }
        return sha1(json_encode($seed));
    }
    
    public function seedNew()
    {
        $maxAttempts = 100;
        $i = 0;
        do {
            $seed = $this->factory->newSeed();
            if(false === $seed){
                return false;
            }
            $seedKey = $this->makeSeedKey($seed);
            
            if (! isset($this->cache[$seedKey])) {
                $this->cache[$seedKey] = 1;
                return $seed;
            }
        } while ($i ++ < $maxAttempts);
        throw new \Exception('SeedsFactory Error : Attempts ' . $maxAttempts . ' no new given');
    }
    
    public function addSeed($seed)
    {
        return $this->cache[strval($seed)] = 1;
    }

    public function seedOld()
    {
        return array_rand($this->cache);
    }

    public function seedsAll()
    {
        return $this->cache;
    }

    public function destorySeeds()
    {
        $this->factory->destoryAllSeed($this->seedsAll());
    }
}