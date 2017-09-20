<?php
namespace Ate\Controller;
use Think\Controller;
class TeacherController extends DomainController {
    /*教师登录*/
    public function login(){
        $username=I('post.loginuser'); //教师名
        $password=I('post.password'); //教师密码  
        $parent=M('teacher'); //教师表
        $where['Username']= $username;
        $where['PassWord']=md5($password);
        $where['_logic'] = 'and';
        $user=$parent->where($where)->find();
        
        if($user){   
            
            $data=$user;
            $schoolid=$user['schoolid'];
            $school=M("school");     
            $res=$school->where("schoolid=$schoolid")->find();
            $data["school"]=$res['schoolname'];

            $this->apiReturn(100,'登录成功',$data);

        }else{
            $data = array(
            'error'=>'用户名或者密码错误'
            );
            $this->apiReturn(0,'登录失败',$data);
        }  
    }
}