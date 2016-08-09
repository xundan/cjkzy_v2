<?php
/**
 * Created by PhpStorm.
 * User: CLEVO
 * Date: 2016/8/9
 * Time: 14:33
 */

namespace Views\Controller;
use Think\Controller\RestController;

class DistributeController extends RestController
{
    public function index(){

    }

    public function create($content){
        $record=array(
            'message'=>$content,
            'type'=>'plain',
            'remark'=>'0',
        );
        D('Distribute')->add($record);
    }
}