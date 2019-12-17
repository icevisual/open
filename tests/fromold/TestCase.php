<?php


class TestCase extends Illuminate\Foundation\Testing\TestCase
{

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        
        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        
        return $app;
    }

    public function postRetJson($uri, array $data = [], array $headers = [])
    {
        empty($headers) && $headers = [
            'Accept' => 'application/json',
        ];
        return $this->post($uri,$data,$headers);
    }
    
    public function sleep($seconds){
        while ($seconds -- ){
//             dump('Sleep for '.($seconds + 1).'s');
            sleep(1);
        }
    }
    
    /**
     * 将结果做json解析
     * 
     * @return Ambigous <mixed, string>
     */
    public function toJson()
    {
        $content = $this->response->getContent();
        $isJson = is_json($content);
        if($isJson){
            return json_decode($content, 1);
        }
        return $this;
    }

    public function output($var)
    {
        dump($var);
        return $this;
    }

    /**
     * Asserts that an json decode array has a value 1 in code field.
     *
     * @param array $jsonArray            
     * @param string $message            
     */
    public static function assertJsonOk($jsonArray, $message = '')
    {
        return self::assertCodeEqual($jsonArray, \ErrorCode::STATUS_OK,$message);
    }

    /**
     * Asserts that a string is a valid json string.
     * 
     * @param string $jsonString            
     * @param string $message            
     */
    public static function assertJson($jsonString, $message = '')
    {
        $constraint = new \App\Extensions\PhpUnit\PHPUnit_Framework_Constraint_Json($jsonString);
        return self::assertThat(true, $constraint, $message);
    }

    /**
     * Asserts that code is equal.
     * 
     * @param array|integer $jsonOrCode
     * @param integer $code
     * @param string $message
     */
    public static function assertCodeEqual($jsonOrCode, $code, $message = '',$failedCallback = null,$successCallback = null)
    {
        $constraint = new \App\Extensions\PhpUnit\PHPUnit_Framework_Constraint_CodeEqual($jsonOrCode,$failedCallback,$successCallback);
        self::assertThat($code, $constraint, $message);
    }
}
