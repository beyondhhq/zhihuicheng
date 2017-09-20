<?php
namespace App\Controller;
use Think\Controller;
class HuodongjiluController extends DomainController {
	/*活动记录范例列表*/
    public function recordlist(){
        $where['category'] = "记录范例";
        $info = M('jilufanli')->where($where)->field('id,title,name,time,nav,small,pic1,pic')
        ->order('time desc')->select();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }

    /*活动记录范例详情*/
    public function recorddetails(){
        $id = $_POST["id"];
        $where["id"] = $id;
        $info = M('jilufanli')->where($where)->field('id,title,name,time,pic,content')->find();
        if ($info) {
            $this->apiReturn(100,'读取成功',$info);
        }else{
            $this->apiReturn(0,'读取成功',"范例不存在");
        }
        
    }

    public function addrealrecord()
    {
        //图片上传start
        $upload = new \Think\Upload();// 实例化上传类
        $upload->autoSub=false;
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Public/Home/images/huodong/'; // 设置附件上传根目录
        $info   =   $upload->upload();
        if($info){
            foreach($info as $file){
                $pict .= $file['savepath'].$file['savename'].',';
            }
            // $pict=explode(',', $pict);
            // $data['pict']=$pict[0];
            // $data['pictone']=$pict[1];
            // $data['picttwo']=$pict[2];
        }
        //图片上传end
        //
        $title=$_POST['title'];
        $content=$_POST['content'];
        $studentid = $_POST['studentid'];
        $studentname = $_POST['studentname'];
        $classid = $_POST['classid'];


        //班级
        $classc['ClassId'] = $classid;
        $classc =M('class')->where($classc)->find();
        $data['gradename']=$classc['grade'];
        $data['classname']=$classc['classname'];
        $data['studentname']=$studentname;
        $data['StudentID']=$studentid;


        $data['picture']= $pict;
        $data['Handline']=$title;
        $data['content']=$content;
        $data['time']=date("Y-m-d H:i:s");
        $data['url']='activity';
        $data['comment']=0;
        $true=M('da_activation_record')->add($data);
        if ($true) {
            $this->apiReturn(100,'读取成功', "上传成功");
        }else{
            $this->apiReturn(0,'读取成功',"上传失败");
        }
    }
}