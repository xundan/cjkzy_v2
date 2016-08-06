<?php
/**
 * Created by PhpStorm.
 * User: CLEVO
 * Date: 2016/7/27
 * Time: 12:20
 */

namespace Views\Controller;
use Think\Controller\RestController;

class RawController extends RestController
{
    protected $allowMethod    = array('get','post','put'); // REST允许的请求类型列表
    protected $allowType      = array('json'); // REST允许请求的资源类型列表

    public function index(){
        $this->createRaw("test");
    }

    public function demo(){
        $this->listRaws();
    }

    Public function messages() {
        $data = array(
            "resultcode"=>"105",
            "reason"=>"应用未审核超时，请提交认证",
            "result"=>null,
            "error_code"=>10005
        );
        switch ($this->_method){
            case 'get': // get请求处理代码
                if ($this->_type == 'html'){
                    echo 'html';
                }elseif($this->_type == 'xml'){
                    echo 'xml';
                }
                echo '<br>restful url is correct.';
                break;
            case 'put': // put请求处理代码
                if ($this->_type == 'json'){

                }
                break;
            case 'post': // post请求处理代码



                $result1 = $GLOBALS['HTTP_RAW_POST_DATA'] ;
                $object2 = json_decode($result1, true);
                $data['resultcode']="201";
                $data['reason']="新建或修改数据成功";
                $data['error_code']="0";
                $data['result']=$result1;
                $data['object2content']=$object2['content'];
//                $data['data_type']=$this->_type;

                $this->createRaw($object2['content']);

                $this->response($data,'json');
                break;
        }
    }


    private function createRaw($content){
        $rawAttribute=array(
            'rid'=>'php_test',
            'content'=>$content,
            'sender'=>'test 01',
            'type'=>'plain',
            'remark'=>'0',
        );
        D('Raw')->add($rawAttribute);
    }

    private function listRaws(){
        dump(D('Raw')->select());
    }

    private function remarkRaw($rawId){
        $rawUpdateAttribute = array(
            'id'=>$rawId,
            'remark'=>'1',
        );
        D('Raw')->save($rawUpdateAttribute);
    }

//    private function deleteRaw($rawId){
//        D('Raw')->delete($rawId);
//    }

    private function showRaw($rawId){
        dump(D('Raw')->find($rawId));
    }
}