<?php
namespace Apa\Controller;
use Think\Controller;
class GaokaogaozhaoController extends DomainController {
	/* 最新资讯*/
    public function news(){
        $provinces=array('北京','天津','河北','山西','内蒙古','辽宁','吉林','黑龙江','上海','江苏','浙江','安徽','福建','江西','山东','河南','湖北','湖南','广东','广西','海南','重庆','四川','贵州','云南','西藏','陕西','甘肃','青海','宁夏','新疆','港澳');
        $province=$_POST['province'];
        if($province){
        	$where['province'] = $_POST['province'];
        }else{
        	$where['province'] = '北京';
        }
		$where['kind'] = 2;
		$sql = M('p_volunteer_encyclopedia');
		$info = $sql->where($where)->field('id,name,time')->order('time desc')->select();

        $data=array(
            'province'=>$provinces,
            'lists'=>$info
        );

        $this->apiReturn(100,'读取成功',$data);
    }
    /*全国资讯*/
    public function quanguo(){
    	$where['kind'] = '3';
    	$Data = M('p_volunteer_encyclopedia');
    	$data = $Data->where($where)->order('time desc')->field('id,name,time')->select();
    	$this->apiReturn(100,'读取成功',$data);
    }
    /*最新资讯 全国资讯 详细页*/
    public function detail(){
    	$where['id'] = $_POST['id'];
		$data = M('p_volunteer_encyclopedia')->where($where)->find();
		$this->apiReturn(100,'读取成功',$data);
    }
    /*专家讲座*/
    public function zjjzlist()
    {
        $where['class'] = 2;
        $where['kind'] = 5;
        $info = M('video')->field('ViideoID,VideoName,image,time, VideocSrc')->where($where)->select();
        $this->apiReturn(100,'读取成功',$info);
    }

}