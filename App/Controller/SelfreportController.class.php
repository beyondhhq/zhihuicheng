<?php
namespace App\Controller;
use Think\Controller;
class SelfreportController extends DomainController {
	//添加 学生自我陈述报告
    public function addziwochenshu(){
        // $aid=implode(',', $_POST['aid']);
            $data['studentid']= $_POST['studentid'];
            $data['studentname']= $_POST['studentname'];
           
            //班级
            $classc['ClassId'] = $_POST['classid'];
            $classc =M('class')->where($classc)->find();
            $data['gradename']=$classc['grade'];
            $data['classname']=$classc['classname'];

            $data['classid']=$_POST['classid'];
            $data['title']=$_POST['title'];
            $data['content']=$_POST['content'];

            // $data['aid']=$aid;
            $data['time']=date('Y-m-d H:i:s',time());

            $true=M('ziwochenshu')->add($data);
            if ($true) {
                $this->apiReturn(100,'读取成功',"发表成功");
            }else{
                $this->apiReturn(0,'读取失败',"发表失败");
            }
        
        
    }
}