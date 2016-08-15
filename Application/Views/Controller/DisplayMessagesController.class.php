<?php
/**
 * Created by PhpStorm.
 * User: LX
 * Date: 2016/8/12
 * Time: 10:29
 */

namespace Views\Controller;

use Think\Controller;

class DisplayMessagesController extends Controller
{


//页面
//    public function index(){
//        $this->display();
//    }

////获取查询位置，默认为1
//    public function id_get(){
//        if(IS_POST){
//            $MessageID = $_POST['id'];
//        }else{
//            $MessageID = 1;
//        }
//
//    }

//    public function connectDb(){
//        $conn = mysqli_connect("localhost","root","Admin123");
//        if(!$conn){
//            echo "connect error";
//        }else{
//            return $conn;
//        }
//
//    }
//////获取查询数据
////    public function data_get($conn){
////            mysqli_select_db('db',$conn);
//////        mysqli_query("SET NAMES 'utf-8'");
////            $sql = "seclect * from messages";
////            $data = mysqli_query($sql);
////            return $data;
////    }
////
//////根据ID输出查询数据
////    public function showMessage($data,$MessageID){
////        $count = mysqli_num_rows($data);
////        if($MessageID>$count){
////            echo "超过最大查询范围";
////        }else{
////            print_r();
////        }
////    }
//
//    public function

    public function showDemo($id){
        $data=D('DisplayMessages')->find($id);
        $this->assign("data",$data);
        $this->assign("id",$id);
        $id_minus = $this->find_prev($data,$id);
        $id_plus = $this->find_next($data,$id);
        $url_prev = U('Views/DisplayMessages/showDemo')."?id=$id_minus";
        $url_next = U('Views/DisplayMessages/showDemo')."?id=$id_plus";
        $this->assign("prev",$url_prev);
        $this->assign("next",$url_next);
        $this->display();
    }

    public function check(){
        echo 'checked';
    }

    public function delete($id){
        echo 'deleted';
    }

    public function find_prev($data,$id)
    {
        return $id - 1;
    }

    public function find_next($data,$id)
    {
        return $id + 1;
    }


}