<?php
namespace App\Extensions\Common;


trait ConstTrait 
{

    /**
     * 获取方法注释
     * 
     * @param unknown $methodName            
     * @return multitype:
     */
    public static function getMethodAnnotation($methodName)
    {
        $export = \ReflectionMethod::export(__CLASS__, $methodName, 1);
        $export = explode('Method [ ', $export);
        if (!$export[0]){
            $export[0] = "\n";
        }else{
            $export[0] = "\n\t".$export[0];
        }
        return "\t" . $export[0];
    }

    /**
     * 自动生成const变量接口
     */
    public static function autoGeneration($funcPrefix = 'parse',$constPrefix = 'parse_',$force = false)
    {
        $ReflectionClass = new \ReflectionClass(__CLASS__);
        // $ConstantsArray = $ReflectionClass->getConstants();
        $InterfaceNames = $ReflectionClass->getInterfaceNames();
        
        $InterfaceFileName = explode("\\", $InterfaceNames[0]);
        $InterfaceFileName = end($InterfaceFileName);
        $ReflectionInter = new \ReflectionClass($InterfaceNames[0]);
        $interConstsArray = $ReflectionInter->getConstants();
        $methodsArray = $ReflectionClass->getMethods();
        $processorConstsArray = [];
        foreach ($methodsArray as $k => $v) {
            $methodName = $v->getName();
            
            if (strpos($methodName, $funcPrefix) === 0 && $funcPrefix !== $methodName) {
                $name = substr($methodName, strlen($funcPrefix));
                $constName = strtoupper($constPrefix.\Illuminate\Support\Str::snake($name));
                $processorConstsArray[$constName] = ucfirst($name);
            }
        }
        $namespace = $ReflectionClass->getNamespaceName();
        $diffArray = array_diff($processorConstsArray, $interConstsArray);
        if ($diffArray || $force) {
            $constString = '';
            $interFileName = __DIR__ . DS . $InterfaceFileName . '.php';
            $eol = PHP_EOL;
            // $eol = "\r\n";
            foreach ($processorConstsArray as $k => $v) {
                $ann = self::getMethodAnnotation($funcPrefix . $v);
                $constString .= "$ann    const $k = '$v';$eol";
            }
            // 有区别,重新生成
            $newContent = <<<EOF
<?php
    
/**
 * Auto
 */
namespace $namespace;
    
interface $InterfaceFileName 
{
$constString
}


EOF;
            
            @file_put_contents($interFileName, $newContent);
        }
    }
    
    
    public static function detectConstName($code){
        $ReflectionClass = new \ReflectionClass(__CLASS__);
        $ConstantsArray = $ReflectionClass->getConstants();
        $ConstantsArray = array_flip($ConstantsArray);
        return isset($ConstantsArray[$code]) ? $ConstantsArray[$code] : false;
    }
    

}















