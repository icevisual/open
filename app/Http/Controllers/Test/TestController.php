<?php

namespace App\Http\Controllers\Test;

use App\Exceptions\ServiceException;
use App\Models\Common\Test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    //

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ServiceException
     * @throws \App\Exceptions\ValidationException
     */
    public function create(Request $request)
    {
        $data =  $request->all();
        runCustomValidator([
            'data' => $data, // 数据
            'rules' => [
                'name' => 'required'
            ]
        ]);
        $t = new Test();
        $obj = $t->CreateNew($data['name']);
        if(!$obj)
            return $this->__json(400);
        return $this->__json();
    }
}
