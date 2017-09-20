<?php
namespace Apa\Controller;
use Think\Controller;
class ParentController extends DomainController {
    /*家长登录*/
    public function login(){
        $username=I('post.loginuser'); //家长名
        $password=I('post.password'); //家长密码    
        $parent=M('parent'); //家长表
        $where['loginuser']= $username;
        $where['password']=md5($password);
        $where['_logic'] = 'and';
        $user=$parent->where($where)->find();
        if($user){
            //县
            $cxian['ProvincesID']=$user['xian'];
            $cxians=M('provinces')->where($cxian)->find();
            //市
            $cshi['ProvincesID']=$cxians['pid'];
            $cshis=M('provinces')->where($cshi)->find();
            //省
            $csheng['ProvincesID']=$cshis['pid'];
            $cshengs=M('provinces')->where($csheng)->find();

            $user['sheng']=$cshengs['provincesname'];
            $pcid=$user['parentcard'];
     
            //根据parentcardid取出学生的姓名和学校
            if(!empty($pcid)){
               $pid['parentcard']=$pcid;
               $stuinfo = M('student')->where($pid)->field('StudentName,SchoolID,StudentID,Img')->find();
               $schid=$stuinfo['schoolid'];
               $ssid['SchoolID']=$schid;
               $schinfo = M('school')->where($ssid)->field('SchoolName')->find();
               $user['img']=$stuinfo['img'];
               $user['studentid']=$stuinfo['studentid'];
               $user['child']=$stuinfo['studentname'];
               $user['childschool']=$schinfo['schoolname'];                
            }else{
               $user['img']="";
               $user['studentid']="";
               $user['child']="";
               $user['childschool']="";

            }
            
            $data=$user;

            $this->apiReturn(100,'登录成功',$data);
        }else{
            $data = array(
            'error'=>'用户名或者密码错误'
            );
            $this->apiReturn(0,'登录失败',$data);
        }  
    }
    /*判断是否为改革省份*/
    public function checkgaige(){
        $parentid=$_POST['parentid'];
        $who['parentid']=$parentid;
        $info=M('parent')->where($who)->find();
        $xian['ProvincesID']=$info['xian'];//xian
        $xians=M('provinces')->where($xian)->find();
        $shi['ProvincesID']=$xians['pid'];//shi
        $shis=M('provinces')->where($shi)->find();
        $sheng['ProvincesID']=$shis['pid'];//sheng
        $shengs=M('provinces')->where($sheng)->find();
        $xsheng=$shengs['provincesname'];//所在sheng
        $wherec['name']=$xsheng;
        $result=M('province_new')->where($wherec)->find();
        if($result){
            $data='1';
        }else{
            $data='0';
        }
        $this->apiReturn(100,'请求成功',$data);
    }
}