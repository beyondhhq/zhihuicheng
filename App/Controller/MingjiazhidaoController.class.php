<?php
namespace App\Controller;
use Think\Controller;
class MingjiazhidaoController extends DomainController {
    /*作文*/
    public function zuowen(){
      $lanmu=array('作文','语文','数学','英语');
      $category=$_POST['category'];
      if($category){
        $category=$_POST['category'];
      }else{
        $category='1';//作文
      }
      $where['cid']=$category;
      $info=M('mjzd_news')->where($where)->order('browse desc')->field('id,cid,category,thumb,title,stitle,abstract,browse,year')->select();
      foreach ($info as $k => $v) {
        $info[$k]['comment']=M('mjzd_comment')->where("{$v['id']}=mid")->count();
      }
      $data=array(
        'lanmu'=>$lanmu,
        'category'=>$category,
        'lists'=>$info
      );
      $this->apiReturn(100,'提交成功',$data);
    }
    /*作文详情*/
    public function detail(){
      $id=$_POST['id'];
      $who['id']=$id;
      $val=array(
          'browse'=>array('exp','browse+1'),
      );
      M('mjzd_news')->where($who)->save($val);//增加浏览次数
      $detail=M('mjzd_news')->where($who)->find();

      $where['mid']=$id; //文章id
      $info=M('mjzd_comment')->where($where)->order('id desc')->select();
      foreach($info as $k=>$v){
            $info[$k]['studentname']=$v['studentname'];
            $info[$k]['content']=$v['content'];
            $info[$k]['likes']=$v['likes'];
            $info[$k]['comment']=$v['comment'];
            $info[$k]['time']=$v['time'];
            $info[$k]['touxiang']=$v['touxiang'];
      }
      $data=array(
        'infos'=>$detail,
        'lists'=>$info
      );
      $this->apiReturn(100,'提交成功',$data);
    }
    /*发表评论*/
    public function comment(){
      $studentid=$_POST['studentid'];
      $student=M('student')->where('studentid='.$studentid)->find();

      $info['mid']=$_POST['id'];
      $info['mtitle']=$_POST['title'];
      $info['content']=$_POST['content'];
      $info['studentid']=$student['studentid'];
      $info['studentname']=$student['studentname'];
      $info['touxiang']=$student['img'];
      $info['time']=date('Y-m-d H:i:s');
      $true=M('mjzd_comment')->add($info);
      if($true){
        $id=M('mjzd_comment')->getLastInsId(); //获取最新id
        $data['id']=$id;
        $data['studentname']=$student['studentname'];
        $data['content']=$_POST['content'];
        $data['time']=date('Y-m-d H:i:s');
        $data['touxiang']=$student['img'];
        $this->apiReturn(100,'提交成功',$data);
      }
    }
    /*点赞*/
    public function dianzan(){
      $studentid=$_POST['studentid'];
      $student=M('student')->where('studentid='.$studentid)->find();

      $which['id']=$_POST['id'];
      $where['pid']=$_POST['id'];
      $where['studentid']=$studentid;
      $info['pid']=$_POST['id'];
      $info['studentid']=$studentid;
      $info['studentname']=$student['studentname'];
      $info['time']=date('Y-m-d H:i:s');
      $true=M('mjzd_dianzan')->where($where)->find();
      if($true){ //已点赞
          M('mjzd_dianzan')->where($where)->delete();
          $val=array(
              'likes'=>array('exp','likes-1'),
          );
          M('mjzd_comment')->where($which)->save($val);
          $count=M('mjzd_comment')->where($which)->find();
          $data['num']=$count['likes'];
          $this->apiReturn(100,'提交成功',$data);
      }else{ //无点赞记录
          M('mjzd_dianzan')->add($info);
          $val=array(
              'likes'=>array('exp','likes+1'),
          );
          M('mjzd_comment')->where($which)->save($val);
          $count=M('mjzd_comment')->where($which)->find();
          $data['num']=$count['likes'];
          $this->apiReturn(100,'提交成功',$data);
      }
    }
    /*获取回复*/
    public function getreply(){
      $info['cid']=$_POST['id'];
      $data=M('mjzd_reply')->where($info)->order('id desc')->select();
      $this->apiReturn(100,'提交成功',$data);
    }
    /*回复评论*/
    public function reply(){
      $studentid=$_POST['studentid'];
      $student=M('student')->where('studentid='.$studentid)->find();

      $ctitle['id']=$_POST['id'];
      $ctitles=M('mjzd_comment')->where($ctitle)->find();

      $info['ctitle']=$ctitles['mtitle'];
      $info['pid']=$ctitles['studentid'];

      $info['cid']=$_POST['id'];
      $info['reply']=$_POST['content'];
      $info['studentid']=$studentid;
      $info['studentname']=$student['studentname'];
      $info['touxiang']=$student['img'];
      $info['time']=date('Y-m-d H:i:s');
      $where['id']=$_POST['id'];
      $comment=M('mjzd_comment');
      $reply=M('mjzd_reply');
      $comment->startTrans();
      $ture1=$reply->add($info);
      $val=array(
          'comment'=>array('exp','comment+1'),
      );
      $ture2=$comment->where($where)->save($val);
      if($ture1 && $ture2){
          $comment->commit();
          $data['studentname']=$student['studentname'];
          $data['touxiang']=$student['img'];
          $data['reply']=$_POST['content'];
          $data['time']=date('Y-m-d H:i:s');
          $count=$comment->where($where)->find();
          $data['comment']=$count['comment'];
          $this->apiReturn(100,'提交成功',$data);
      }else{
          $comment->rollback();
      }
    }
    /*推荐2条*/
    public function hot(){
      $where1['category']='作文';
      $where2['category']='数学';
      $data1=M('mjzd_news')->order('id desc')->limit(1)->field('id,category,thumb,title,stitle,abstract,browse,year')->where($where1)->select();
      $data2=M('mjzd_news')->order('id desc')->limit(1)->field('id,category,thumb,title,stitle,abstract,browse,year')->where($where2)->select();
      $data=array_merge($data1,$data2);
      foreach ($data as $k => $v) {
        $data[$k]['comment']=M('mjzd_comment')->where("{$v['id']}=mid")->count();
      }
      $this->apiReturn(100,'提交成功',$data);
    }
}