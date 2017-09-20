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
}