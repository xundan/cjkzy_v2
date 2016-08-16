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

    public function showDemo($id)
    {
        // 有where包装的时候不要直接find($id)，否则where会失效
        $data = D('DisplayMessages')->where("invalid_id=0 AND status=0 AND id=" . $id)->find();
        $this->assign("data", $data);
        $this->assign("id", $id);
        $id_minus = $this->find_prev($id);
        $id_plus = $this->find_next($id);
        if (!$data) {
            echo "<br><h1>没有更多数据了。</h1>";
        }

        if ($id_minus == -1) {
            echo "<br><h1>已经是第一条了。</h1>";
            $id_minus = $id;
        }
        if ($id_plus == -1) {
            echo "<br><h1>已经是最后一条了。</h1>";
            $id_plus = $id;
        }
        $url_prev = U('Views/DisplayMessages/showDemo') . "?id=$id_minus";
        $url_next = U('Views/DisplayMessages/showDemo') . "?id=$id_plus";
//        $url_delete = U('Views/DisplayMessages/delete')."?id=$id";
        $this->assign("prev", $url_prev);
        $this->assign("next", $url_next);
        $this->display();
    }

    public function check($id)
    {

        $tags = I('post.tag');
        $Relations = D('RelationLabel');
        $all_commit = true;
        foreach ($tags as $tag) {
            $rid = 'MSSG' . $id;
            $data['object_rid'] = $rid;
            $data['label_name'] = $tag;
            $data['invalid_id'] = 0;
//            echo '<br>'.$tag;
            $result = null;
            if ($this->exist($data['object_rid'], $data['label_name'])) {
                $result = $Relations->
                where("object_rid='%s' and label_name='%s'", array($rid, $tag))->save($data);
            } else {
                $result = $Relations->add($data);
            }
            if (!$result) { // 如果有一个标签插入失败
                // TODO 记录日志
                $all_commit = false;
            }
        }
        if ($all_commit) { // 标签全部更新了才去更新小消息状态

            $update_trans = array(
                "id" => $id,
                "status" => 102,
            );
            $check = D('Message')->save($update_trans);
            if ($check == false) {
                //TODO 写日志
            }
        } else {
            // TODO 写日志
        }

    }

    private function exist($rid, $label_name)
    {
        if (D('RelationLabel')->
        where("object_rid='%s' and label_name='%s'", array($rid, $label_name))->find()
        ) {
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $update_delete = array(
            "id" => $id,
            "status" => -1,
            "invalid_id" => 2,
        );

        if (D('DisplayMessages')->save($update_delete)) echo 'deleted';
    }

    public function find_prev($id)
    {
        if ($id < 1) return -1;
        $prev = D('DisplayMessages')->where("invalid_id=0 AND status=0 AND id<" . $id)
            ->order('id desc')->find();
        if ($prev) {
            return $prev['id'];
        } else {
            return -1;
        }
    }

    public function find_next($id)
    {
        $next = D('DisplayMessages')->where("invalid_id=0 AND status=0 AND id>" . $id)
            ->order('id asc')->find();
        if ($next) {
            return $next['id'];
        } else {
            return -1;
        }
    }


}