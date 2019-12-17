<?php
namespace App\Models\Open;

use App\Models\BaseModel;
use Illuminate\Database\Schema\Blueprint;

class DemoErrorLog extends BaseModel
{
    //
    protected $table = 'demo_error_log';

    public $timestamps = true;

    public $guarded = [];

    
    public static function addRecord($content)
    {
        $allowFields = [
            'datetime',
            'errortype',
            'errormsg',
            'developer_access',
            'device_name',
            'device_access',
            'api_name',
            'req_seq',
            'req_timeout',
            'req_params',
            'loopPlayConfig',
            'extra'
        ];
        $allowFieldsMap = array_flip($allowFields);
        $createData = [];
        $extra = [];
        foreach ($content as $k => $v) {
            if (isset($allowFieldsMap[$k])) {
                if (is_array($v)) {
                    $createData[$k] = json_encode($v);
                } else {
                    $createData[$k] = $v;
                }
            } else {
                $extra[$k] = $v;
            }
        }
        $createData['extra'] = json_encode($extra);
        if (!\Schema::hasTable('demo_error_log')) {
            \Schema::create('demo_error_log',function(Blueprint $table) use($allowFields){
                $table->integer('id',true,true);
                $table->charset = 'utf8';
                $table->collation = 'utf8_general_ci';
                foreach ($allowFields as $v){
                    $table->string($v,255)->nullable();
                }
                $table->timestamps();
            });
        }
        return self::create($createData);
//         $data = [
//             'content' => json_encode($content)
//         ];
//         return self::create($data);
    }
}
