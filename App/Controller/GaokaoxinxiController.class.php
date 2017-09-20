<?php
namespace App\Controller;
use Think\Controller;
class GaokaoxinxiController extends DomainController {
    /*全国资讯*/
    public function quanguozixun(){
        $wheret['kind'] = 3;
        $data = M('p_volunteer_encyclopedia_three')->field('source,id,name,time')->where($wheret)->order('time desc')->select();
        $this->apiReturn(100,'读取成功',$data);
    }
    /*志愿百科*/
    public function zhiyuanbaike(){
        $where['id'] = array('neq',7);
        $where['kind'] = 1;
        $data = M('p_volunteer_encyclopedia_one')->field('source,id,name,time')->where($where)->order('time desc')->select();
        $this->apiReturn(100,'读取成功',$data);
    }
    /*地方资讯*/
    public function difangzixun(){
        $provinces=array('北京','天津','河北','山西','内蒙古','辽宁','吉林','黑龙江','上海','江苏','浙江','安徽','福建','江西','山东','河南','湖北','湖南','广东','广西','海南','重庆','四川','贵州','云南','西藏','陕西','甘肃','青海','宁夏','新疆','港澳');
        $province=$_POST['province'];
        if($province){
            $where['province'] = $_POST['province'];
        }else{
            $where['province'] = '北京';
        }
        $where['kind'] = 2;
        $infos = M('p_volunteer_encyclopedia_two')->field('source,id,name,time')->where($where)->order('time desc')->select();
        $data=array(
            'province'=>$provinces,
            'lists'=>$infos
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*全国查看详情*/
    public function allinfos(){
        $where['id'] = $_POST['id'];
        $data = M('p_volunteer_encyclopedia_three')->where($where)->find();
        $this->apiReturn(100,'读取成功',$data);
    }

    /*地方资讯查看详情*/
    public function provinceinfos(){
        $where['id'] = $_POST['id'];
        $data = M('p_volunteer_encyclopedia_two')->where($where)->find();
        $this->apiReturn(100,'读取成功',$data);
    }

    /*志愿百科查看详情*/
    public function baikeinfos(){
        $where['id'] = $_POST['id'];
        $data = M('p_volunteer_encyclopedia_one')->where($where)->find();
        $this->apiReturn(100,'读取成功',$data);
    }


}