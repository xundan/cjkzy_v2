<?php
/**
 * Created by PhpStorm.
 * User: CLEVO
 * Date: 2016/8/24
 * Time: 17:29
 */

namespace Views\Controller;

use Think\Controller\RestController;

class PullMessagesController extends RestController
{
    protected $allowMethod = array('get', 'post', 'put'); // REST允许的请求类型列表
    protected $allowType = array('json'); // REST允许请求的资源类型列表

    public function index()
    {
        echo "pull available";
    }

    Public function pull($wx)
    {
        $data = array(
            "result_code" => "105",
            "reason" => "应用未审核超时，请提交认证",
            "result" => null,
            "message" => null,
            "error_code" => 10005,
        );
        switch ($this->_method) {
            case 'get': // get请求处理代码
                $msg = $this->get_message($wx);
                if ($msg) {
                    $data['message'] = "【" . $msg['category'] . "】" . $msg['content'];
                    $data['result_code'] = "201";
                    $data['reason'] = "获取成功";
                    $data['result'] = "OK";
                    $data['error_code'] = 0;
                } else {
                    $data['message'] = "";
                    $data['result_code'] = "501";
                    $data['reason'] = "内部错误";
                    $data['result'] = "FAIL";
                    $data['error_code'] = 10501;
                }
                $this->response($data, 'json');
                break;
        }
    }

    // 有些不标准，get方法修改msg2wx关系表状态，以后应改为post方法
    Public function used($wx, $msg_id)
    {
        $data = array(
            "result_code" => "105",
            "reason" => "应用未审核超时，请提交认证",
            "result" => null,
            "message" => null,
            "error_code" => 10005,
        );
        switch ($this->_method) {
            case 'get':
                $update_data['invalid_id'] = 100;
                $update = D('RelationM2W')->where("invalid_id=0 AND msg_id=$msg_id AND wx='$wx'")
                    ->save($update_data);
                if ($update) {
                    $data['result_code'] = "201";
                    $data['reason'] = "修改数据成功";
                    $data['error_code'] = "0";
                    $data['result'] = 'OK';
                    $data['message'] = "wx:" . $wx . ",msg_id:" . $msg_id;
                } elseif ($update === false) { //更新出错
                    $data['message'] = "";
                    $data['result_code'] = "501";
                    $data['reason'] = "内部错误";
                    $data['result'] = "FAIL";
                    $data['error_code'] = 10501;
                } else { // 更新行数为0
                    $data['message'] = "";
                    $data['result_code'] = "404";
                    $data['reason'] = "没有可更新的数据";
                    $data['result'] = "OK";
                    $data['error_code'] = 10404;
                }
                $this->response($data, 'json');
                break;
        }
    }


    /**
     * @param $wx
     * @return string
     */
    private function get_message($wx)
    {
        if (!$wx) return null;
        $relation = D("RelationM2W")->where("invalid_id=0 AND wx='" . $wx . "'")->find();
        if ($relation) {
            $msg_id = $relation['msg_id'];
            $msg = D('message')->find($msg_id);
            return $msg;

        }
        return false;
    }


}