<?php
namespace App\Controller;
use Think\Controller;
class GaokaoxinzhengController extends DomainController {
    /*高考新政*/
    public function lists(){
        $province=array('北京','天津','河北','山西','内蒙古','辽宁','吉林','黑龙江','上海','江苏','浙江','安徽','福建','江西','山东','河南','湖北','湖南','广东','广西','海南','重庆','四川','贵州','云南','西藏','陕西','甘肃','青海','宁夏','新疆');
        if($_POST['province']){
            $str=$_POST['province'];
            $pro=mb_substr($str,0,2,'utf-8');
            $where['province'] = array('like',"%$pro");
            $lists=M('policy')->where($where)->select();       
        }else{
            $lists=M('policy')->select();
        }

        $data=array(
            'aa'=>$pro,
        'province'=>$province,
        'lists'=>$lists
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*查看省份新政*/
    public function infos(){
        $where['url'] = $_POST['url'];
        $sql = M('policy');
        $info = $sql->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }

}