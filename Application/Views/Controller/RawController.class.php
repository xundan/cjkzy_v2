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
//        $this->createRaw("test");
    }

    public function demo(){
        $this->listRaws();
    }

    Public function messages() {
        $data = array(
            "result_code"=>"105",
            "reason"=>"应用未审核超时，请提交认证",
            "result"=>null,
            "message_id"=>null,
            "error_code"=>10005,
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
//                if ($this->_type == 'json'){
//
//                }
                break;
            case 'post': // post请求处理代码

                $result1 = $GLOBALS['HTTP_RAW_POST_DATA'] ;
                $object2 = json_decode($result1, true);

                if ($object2){
                    $content=$object2['content'];
                    $sender=$object2['sender'];
                    $sender_wx=$object2['sender_wx'];
                    date_default_timezone_set('PRC');
                    $title = date('y-m-d_h:i',time());
                    $pattern = '/@\s*(.+)\s*\#\$([\s\S]*[0-9]{11}[\s\S]*)/';
                    $res=preg_match($pattern,$content,$match);
                    $owner=null;
                    if ($res) {
                        $owner=$match[1];
                        $content=$match[2];
                    } else {
                        // TODO 不符合格式的消息 通知开发检查手机端过滤逻辑
                        $data['result_code']="301";
                        $data['reason']="数据格式不合法，本地过滤失效";
                        $data['error_code']="10301";
                        $data['message_id']=$title;
                        $data['result']=$object2['content'];
                    }
                    $title.=substr($content,0,26);

                    $insert = $this->createRaw($title, $content, $owner, $sender, $sender_wx);
                    if($insert){
                        $data['result_code']="201";
                        $data['reason']="新建或修改数据成功";
                        $data['error_code']="0";
                        $data['message_id']=$title;
                        $data['result']=$object2['content'];
                    }else{
                        // TODO 插入失败，通知开发人员
                        $data['result_code']="106";
                        $data['reason']="数据库操作错误";
                        $data['error_code']="10006";
                        $data['message_id']=$title;
                        $data['result']=$object2['content'];
                    }
                }else{
                    //TODO 数据为空，网络错误，客户端错误，通知开发人员
                    $data['result_code']="500";
                    $data['reason']="内部错误";
                    $data['message_id']=null;
                    $data['error_code']="10500";
                    $data['result']=null;
                }

                $this->response($data,'json');
                break;
        }
    }

    // TODO targets
    Public function targets() {
        $data = array(
            "result_code"=>"105",
            "reason"=>"应用未审核超时，请提交认证",
            "result"=>null,
            "message_id"=>null,
            "error_code"=>10005,
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
//                if ($this->_type == 'json'){
//
//                }
                break;
            case 'post': // post请求处理代码

                $result1 = $GLOBALS['HTTP_RAW_POST_DATA'] ;
                $object2 = json_decode($result1, true);

                if ($object2){
                    $endtime=$object2['endtime'];
                    $mmaxvalue=$object2['mmaxvalue'];
                    $mdate=$object2['mdate'];
                    $starttime=$object2['starttime'];
                    $yesterdayvalue=$object2['yesterdayvalue'];
                    $description=$object2['description'];
                    $name=$object2['name'];
                    $isagenda=$object2['isagenda'];
                    $isdone=$object2['isdone'];
                    $mvalue=$object2['mvalue'];
                    $minterval=$object2['minterval'];
                    $todayvalue=$object2['todayvalue'];
                    $insert = $this->createTarget($name,$mdate,$starttime,$endtime,$mmaxvalue,$yesterdayvalue,$description
                        , $isagenda, $isdone, $mvalue, $minterval, $todayvalue);
                    if($insert){
                        $data['result_code']="201";
                        $data['reason']="新建或修改数据成功";
                        $data['error_code']="0";
                        $data['message_id']=$name.$mdate;
                        $data['result']=$object2['content'];
                    }else{
                        // TODO 插入失败，通知开发人员
                        $data['result_code']="106";
                        $data['reason']="数据库操作错误";
                        $data['error_code']="10006";
                        $data['message_id']=$name;
                        $data['result']=$object2['content'];
                    }
                }else{
                    //TODO 数据为空，网络错误，客户端错误，通知开发人员
                    $data['result_code']="500";
                    $data['reason']="内部错误";
                    $data['message_id']=null;
                    $data['error_code']="10500";
                    $data['result']=null;
                }

                $this->response($data,'json');
                break;
        }
    }


    private function listRaws(){
        dump(D('Raw')->select());
    }

    /**
     * @param $title
     * @param $content
     * @param $owner
     * @param $sender
     * @param $sender_wx
     * @return mixed    成功与否
     */
    public function createRaw($title, $content, $owner, $sender, $sender_wx)
    {
        //解决短时间内重复调用问题
        $duplicate_data = D('Raw')->where("rid = '$title'")->find();
        if($duplicate_data){
            return $duplicate_data;
        }

        $rawAttribute = array(
            'rid' => $title,
            'content' => $content,
            'sender' => $sender,
            'type' => 'plain',
            'remark' => '0',
            'status'=>0,
            'owner' => $owner,
            'sender_wx'=>$sender_wx,
        );
        $insert = D('Raw')->add($rawAttribute);
        return $insert;
    }

    public function createTarget($name, $date, $starttime, $endtime, $mmaxvalue,
        $yesterdayvalue, $description, $isagenda, $isdone, $mvalue, $minterval, $todayvalue)
    {
        $rawAttribute = array(
            "name"=>$name,
            "mdate"=>$date,
            "starttime"=>$starttime,
            "endtime"=>$endtime,
            "description"=>$description,
            "mvalue"=>$mvalue,
            "isagenda"=>$isagenda,
            "minterval"=>$minterval,
            "mmaxvalue"=>$mmaxvalue,
            "todayvalue"=>$todayvalue,
            "yesterdayvalue"=>$yesterdayvalue,
            "isdone"=>$isdone,
        );
        $insert = D('Targets')->add($rawAttribute);
        return $insert;
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