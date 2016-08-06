<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function _before_index(){
        echo "before index<br>";
    }

    public function index(){
//        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');

//        echo "test this!";

        $this->listActionUrl();
    }


    public function _after_index(){
        echo "after index<br>";
    }

    public function listcjkzy(){
//        http://localhost/cjkzy_v1/index.php/Home/Index/list
        echo "list<br>";
    }

    private function listActionUrl(){

        //URL模式
        echo "current model: ".C('URL_MODEL'); //在config里编辑 0：带参数，1：默认，2：rewrite
        echo '<hr>';

        echo "User/index: <a href=\"".U('Home/User/index')."\">".U('Home/User/index')."</a>";
        echo '<hr>';
        echo "User/edit: <a href=\"".U('Home/User/edit')."\">".U('Home/User/edit')."</a>";
        echo '<hr>';
        echo "User/login: <a href=\"".U('Home/User/login')."\">".U('Home/User/login')."</a>";
        echo '<hr>';
    }
}