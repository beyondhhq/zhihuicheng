<?php
namespace Apa\Controller;
use Think\Controller;
class GaokaogaozhaoController extends DomainController {
	/* 最新资讯*/
    public function news(){
        $provinces=array('北京','天津','河北','山西','内蒙古','辽宁','吉林','黑龙江','上海','江苏','浙江','安徽','福建','江西','山东','河南','湖北','湖南','广东','广西','海南','重庆','四川','贵州','云南','西藏','陕西','甘肃','青海','宁夏','新疆','港澳');
        $province=$_POST['province'];
        $studentid=$_POST['studentid'];
        $who['StudentID']=$studentid;
        $user=M('student')->where($who)->field('Xian')->find();
        $cxian['ProvincesID']=$user['xian'];
        $cxians=M('provinces')->where($cxian)->find();
        //市
        $cshi['ProvincesID']=$cxians['pid'];
        $cshis=M('provinces')->where($cshi)->find();
        //省
        $csheng['ProvincesID']=$cshis['pid'];
        $cshengs=M('provinces')->where($csheng)->field('ProvincesName')->find();
        $provincename=$cshengs['provincesname'];
        if($province){
        	$where['province'] = $_POST['province'];
        }else{
            if($provincename=="新疆维吾尔自治区"){
               $newprovincename="新疆";
            }elseif($provincename=="西藏自治区"){
               $newprovincename="西藏";
            }elseif($provincename=="宁夏回族自治区"){
               $newprovincename="宁夏";
            }elseif($provincename=="广西壮族自治区"){
               $newprovincename="广西";
            }elseif($provincename=="内蒙古自治区"){
               $newprovincename="内蒙古";
            }elseif($provincename=="香港特别行政区"){
               $newprovincename="港澳";
            }elseif($provincename=="澳门特别行政区"){
               $newprovincename="港澳";
            }else{
               $a=mb_strlen( $provincename,'utf8' );
               $newprovincename=mb_substr( $provincename,0,$a-1,'utf8' );
            }
        	$where['province'] = $newprovincename;
        }
		$where['kind'] = 2;
		$sql = M('p_volunteer_encyclopedia_two');
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
    	$Data = M('p_volunteer_encyclopedia_three');
    	$data = $Data->where($where)->order('time desc')->field('id,name,time')->select();
    	$this->apiReturn(100,'读取成功',$data);
    }
    /*全国资讯 详细页*/
    public function detail(){
    	$type=$_POST['type'];
        $where['id'] = $_POST['id'];
        if($type=="地方"){
           $data = M('p_volunteer_encyclopedia_two')->where($where)->find();
        }
        if($type=="国家"){
           $data = M('p_volunteer_encyclopedia_three')->where($where)->find();
        }
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