<?php
namespace Ate\Controller;
use Think\Controller;
class ZhengceController extends DomainController {
    /*教师登录*/
    public function tuijian(){
            $tid=$_POST['tid'];
            $user=M('teacher')->where("TeacherID=$tid")->field("xian")->find();

             //县
            $cxian['ProvincesID']=$user['xian'];
            $cxians=M('provinces')->where($cxian)->find();
            //市
            $cshi['ProvincesID']=$cxians['pid'];
            $cshis=M('provinces')->where($cshi)->find();
            //省
            $csheng['ProvincesID']=$cshis['pid'];
            $cshengs=M('provinces')->where($csheng)->find();
            if($cshengs){
              //有省市信息推荐本省的的两条河全国的两条
              $province=$cshengs['provincesname'];
              $length=mb_strlen( $province, "utf8" )-1;
              $where['province'] =mb_substr( $province, 0, $length, "utf8" );
              
              $sql = M('p_volunteer_encyclopedia_two');
              $info = $sql->where($where)->field('id,name,time')->order('time desc')->limit(3)->select();
              $newinfo=array();
              if($info){
                 foreach($info as $k=>$v){

                      $v['type']="地方";
                     
                      $newinfo[$k]=$v;
                 }
              }else{
                 $info2=$sql->where()->field('id,name,time')->order('time desc')->limit(3)->select();
                 if($info2){
                 foreach($info2 as $k=>$v){

                      $v['type']="地方";
                     
                      $newinfo[$k]=$v;
                 }
              }
              }
                   
              $sqll= M('p_volunteer_encyclopedia_three');
              $country = $sqll->where(1)->field('id,name,time')->order('time desc')->limit(3)->select();
              $newcountry=array();
              if($country){
                 foreach($country as $k=>$v){

                      $v['type']="国家";
                     
                      $newcountry[$k]=$v;
                 }
              }
              $data=array_merge($newinfo,$newcountry);
              $this->apiReturn(100,'读取成功',$data);

            }else{
              //没有省市信息推荐最新的两条和全国的两条
              $sql = M('p_volunteer_encyclopedia_two');
              $info = $sql->where(1)->field('id,name,time')->order('time desc')->limit(3)->select();
              $newinfo=array();
              if($info){
                 foreach($info as $k=>$v){

                      $v['type']="地方";
                     
                      $newinfo[$k]=$v;
                 }
              }
              $sqll= M('p_volunteer_encyclopedia_three');
              $country = $sqll->where(1)->field('id,name,time')->order('time desc')->limit(3)->select();
              $newcountry=array();
              if($country){
                 foreach($country as $k=>$v){

                      $v['type']="国家";
                     
                      $newcountry[$k]=$v;
                 }
              }
              $data=array_merge($newinfo,$newcountry);

              $this->apiReturn(100,'读取成功',$data);

            }
            
    }
    public function detail(){
        //id为资讯id
        //$type为数据来源类型，2为地方，3为全国
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
    public function zixun(){
        $where['TeacherID']=$_POST['teacherid'];
        $sxian=M('student')->field('Studentid')->where($where)->find();
		
		    $provinces=array('北京','天津','河北','山西','内蒙古','辽宁','吉林','黑龙江','上海','江苏','浙江','安徽','福建','江西','山东','河南','湖北','湖南','广东','广西','海南','重庆','四川','贵州','云南','西藏','陕西','甘肃','青海','宁夏','新疆','港澳');
        $newprovincename=$_POST['province'];
        $studentid=$sxian['studentid'];
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
        if($newprovincename){
        	$where['province'] = $newprovincename;
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
            'aa'=>$newprovincename,
            'province'=>$provinces,
            'lists'=>$info
        );

        $this->apiReturn(100,'读取成功',$data);
        
    }
    public function zhengce(){
        $where['TeacherID']=$_POST['teacherid'];
        $sxian=M('student')->field('Studentid')->where($where)->find();
        $studentid=$sxian['studentid'];
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

        $provinces=array('北京','天津','河北','山西','内蒙古','辽宁','吉林','黑龙江','上海','江苏','浙江','安徽','福建','江西','山东','河南','湖北','湖南','广东','广西','海南','重庆','四川','贵州','云南','西藏','陕西','甘肃','青海','宁夏','新疆');
        if($_POST['teacherid']){
            if($_POST['province']){
              $province=$_POST['province'];
            }else{
              $province=$provincename;
            }
            $str=$province;
            $pro=mb_substr($str,0,2,'utf-8');
            $wheres['province'] = array('like',"%{$pro}%");
            $lists=M('policy')->where($wheres)->select();       
        }else{
            $lists=M('policy')->select();
        }

        $data=array(
            'aa'=>$pro,
        'province'=>$provinces,
        'lists'=>$lists
        );
        $this->apiReturn(100,'读取成功',$data);

    }
}