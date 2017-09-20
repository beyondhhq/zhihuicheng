<?php
namespace Apa\Controller;
use Think\Controller;
class TextController extends DomainController {
    public function a(){
        $m=10;
        $where['cid']=array('between','10,100');
       
        $a=M("student")->where($where)->order("cid asc")->select();
        print_R($a);
        $b=count($a);
        echo $b;
    } 
}