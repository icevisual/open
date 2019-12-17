<?php
namespace SmellOpen;

class Autoloader
{

    public $fileList = [
    ];

    public $classMap = [
        'SmellOpen' => 'src',
    ];

    public function define()
    {
        defined('DS') or define('DS', DIRECTORY_SEPARATOR);
        defined('_SO_SDK_ROOT_') or define('_SO_SDK_ROOT_', __DIR__ . DS );
    }

    public function includeFile($filename)
    {
        $filename = ltrim($filename, '/');
        $filename = ltrim($filename, '\\');
        include _SO_SDK_ROOT_ . $filename;
    }

    public function getFileList()
    {
        return $this->fileList;
    }

    public function getClassMap()
    {
        return $this->classMap;
    }

    /**
     * Register autoload() as an SPL autoloader.
     *
     * @see self::autoload
     */
    public function register()
    {
        $this->define();
        foreach ($this->getFileList() as $v) {
            $this->includeFile($v);
        }
        spl_autoload_register(array(
            $this,
            'autoload'
        ));
    }

    public function loadClassFromPath($namespace, $class, $path)
    {
        $filename = substr($class, strlen($namespace) + 1) . '.php';
        $filename = str_replace(['/','\\'], [DS,DS], $filename);
        $file = _SO_SDK_ROOT_. $path.DS. $filename;
        if(file_exists($file)){
            include $file;
        }
    }

    /**
     * Autoload Psy classes.
     *
     * @param string $class            
     */
    public function autoload($class)
    {
        foreach ($this->getClassMap() as $namespace => $path) {
            if (0 === strpos($class, $namespace)) {
                $this->loadClassFromPath($namespace, $class, $path);
                return true;
            }
        }
    }
}
$Autoloader = new Autoloader();
$Autoloader->register();
