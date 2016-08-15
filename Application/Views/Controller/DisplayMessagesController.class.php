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
        // 有where包装的时候不要直接find($id)，否则where会失效
        $data=D('DisplayMessages')->where("invalid_id=0 AND status=0 AND id=".$id)->find();
        $this->assign("data",$data);
        $this->assign("id",$id);
        $id_minus = $this->find_prev($id);
        $id_plus = $this->find_next($id);
        if($data){
            echo "<br><h1>没有更多数据了。</h1>";
        }

        if($id_minus==-1){
            echo "<br><h1>已经是第一条了。</h1>";
            $id_minus = $id;
        }
        if($id_plus==-1){
            echo "<br><h1>已经是最后一条了。</h1>";
            $id_plus = $id;
        }
        $url_prev = U('Views/DisplayMessages/showDemo')."?id=$id_minus";
        $url_next = U('Views/DisplayMessages/showDemo')."?id=$id_plus";
//        $url_delete = U('Views/DisplayMessages/delete')."?id=$id";
        $this->assign("prev",$url_prev);
        $this->assign("next",$url_next);
        $this->display();
    }

    public function check($id){
//        echo 'checked';
//        $this->showDemo(0);
        $tags = I('post.tag');
        $tags=I('post.id');
        foreach ($tags as $tag){
            echo '<br>'.$tag;
        }
    }

    public function delete($id){
        $update_delete=array(
            "id"=>$id,
            "status"=>-1,
            "invalid_id"=>2,
        );

        if(D('DisplayMessages')->save($update_delete)) echo 'deleted';
    }

    public function find_prev($id)
    {
        if ($id<1) return -1;
        $prev = D('DisplayMessages')->where("invalid_id=0 AND status=0 AND id<".$id)
            ->order('id desc')->find();
        if($prev){
            return $prev['id'];
        }else{
            return -1;
        }
        return $id - 1;
    }

    public function find_next($id)
    {
        $next = D('DisplayMessages')->where("invalid_id=0 AND status=0 AND id>".$id)
            ->order('id asc')->find();
        if($next){
            return $next['id'];
        }else{
            return -1;
        }
    }


}