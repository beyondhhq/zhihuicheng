<?php
namespace App\Controller;
use Think\Controller;
class ZonghesuyangtishengController extends DomainController{

  /*核心价值观 知书明理列表*/
  public function zsmllists(){
      $where['category']='阅读分享';
      $data=M('books')->where($where)->field('id,name,likes,comments,time,thumb')->order('time DESC')->select();
      $this->apiReturn(100,'读取成功',$data);
  }

  /*核心价值观 知书明理详情 评论列表*/
  public function zsmlinfos(){

    // 获取文章详情
    $book['id']=$_POST['id'];
    $infos=M('books')->where($book)->find();

    // 我是否点赞过
    $where["studentid"] = $_POST['studentid'];
    $where["bid"] = $_POST['id'];
    $true=M('books_dianzan')->where($where)->find();
    if ($true) {
      $infos["myzan"]=1;
    }else{
      $infos["myzan"]=0;
    }

    // 获取评论列表
    $where1["booksID"] = $_POST['id'];
    $pl=M('books_restores')->where($where1)->order('restore_time desc')->select();
    foreach ($pl as $k => $v) {
      $pl[$k]['pic']=M('student')->field("img")->find("{$v['restore_uid']}=studentid")["img"];
    }

    $data["info"] = $infos;
    $data["comment"] = $pl;
    $this->apiReturn(100,'读取成功',$data);
  }

  /*核心价值观 知书明理 点赞*/
    public function zansnum(){
        $id=$_POST['bookid'];
        $where['bid']=$id;
        $where['studentid']=$_POST['studentid'];
        $true=M('books_dianzan')->where($where)->find();
        if($true){//已有点赞记录
            M('books_dianzan')->where($where)->delete();
            $wher['id']=$id;
            $dat=array(
                    'likes' => array('exp','likes-1'),
                );
            M('books')->where($wher)->save($dat);
            $count=M('books')->where($wher)->find();
            $data['count']=$count['likes'];
            $data["myzan"] = 0;
            $this->apiReturn(100,'读取成功',$data);
        }else{ //无点赞记录
            $zan['bid']=$id;
            $zan['studentid']=$_POST['studentid'];
            $zan['studentname']=$_POST['studentname'];
            $zan['time']=date('Y-m-d H:i:s');
            M('books_dianzan')->add($zan);
            $wher['id']=$id;
            $dat=array(
                    'likes' => array('exp','likes+1'),
                );
            M('books')->where($wher)->save($dat);
            $count=M('books')->where($wher)->find();
            $data['count']=$count['likes'];
            $data["myzan"] = 1;
            $this->apiReturn(100,'读取成功',$data);
        }
    }

    /*核心价值观 知书明理 评论*/
    public function message(){
        $message=$_POST['message'];
        $data['booksID'] = $_POST['bookid'];
        $data['RestoreBody'] = $message;
        $data['Restore_UId'] = $_POST['studentid']; 
        $data['Restore_Uname'] = $_POST['studentname'];
        $data['Restore_Time'] = date('Y-m-d H:i:s');
        $true=M('books_restores')->add($data);
        if($true){
                $wher['id']=$_POST['bookid'];
                $dat=array(
                    'comments' => array('exp','comments+1'),
                );
                M('books')->where($wher)->save($dat);

                $infos=M('books_restores')->order('ID DESC')->find();
                $data = [];
                $data['restore_uname'] = $infos['restore_uname'];
                $data['content'] = $infos['restorebody'];
                $data['restore_time'] = $infos['restore_time'];

                $where['booksID'] = $_POST['bookid'];
                $count=M('books_restores')->where($where)->count(); //总记录数
                $data['count']=$count;

                $this->apiReturn(100,'读取成功',$data);
                
        }
    }

    /*核心价值观 传统文化 视频列表*/
    public function chuantonglist()
    {
      $where["class"] = 2;
      $where["kind"] = 1;
      $data=M('video')->where($where)
      ->field('viideoid,videocsrc,videoname,image')->order('time DESC')->select();
      $this->apiReturn(100,'读取成功',$data);
    }

    /*核心价值观 传统文化 心理健康 学哥学姐说 获取简介*/
    public function videoinfos()
    {
      $where["ViideoID"] = $_POST["id"];
      $true=M('video')->where($where)
      ->field('viideoid,videoname,sample')->find();
      if ($true) {
        $this->apiReturn(100,'读取成功',$true);
      }else{
        $this->apiReturn(0,'读取失败',"无此记录");
      }
    }

    /*心理健康 学哥学姐说 视频列表*/
    public function xgxjvideolist()
    {
      $where["class"] = 2;
      $where["kind"] = 2;
      $data=M('video')->where($where)
      ->field('viideoid,videocsrc,videoname,image')->order('time DESC')->select();
      $this->apiReturn(100,'读取成功',$data);
    }

    /*心理健康 心理定律导读列表*/
    public function xinli_list(){
      $where['class'] = 1;
      $info = M('d_mentality')->where($where)
      ->field('id,name,looks,comments,time,pict')->order('time desc')->select();
      $this->apiReturn(100,'读取成功',$info); 
    }

    /*心理健康 心理定律导读详情*/
    public function xinli_detail(){
      $id = $_POST['id'];
      $where['id'] = $id;
      $val=array(
            'looks'=>array('exp','looks+1'),
      );
      M('d_mentality')->where($where)->save($val);//增加浏览次数
      $info = M('d_mentality')->where($where)->field("id, name, intro, content,video")->find();

      $where1['mid']=$id; //文章id
      $comment=M('xldd_comment')->where($where1)->field("id,studentname, content, likes, comment, time, touxiang")->order('id desc')->select();

      $data["comment"] = $comment;
      $data["info"] = $info;
      $this->apiReturn(100,'读取成功',$data); 
    }

    /*心理健康 心理定律导读 发表评论*/
   public function addcomment(){
      $info['mid']=$_POST['id'];
      $info['mtitle']=$_POST['title'];
      $info['content']=$_POST['content'];
      $info['studentid']=$_POST['studentid'];
      $info['studentname']=$_POST['studentname'];
      $info['touxiang']=$_POST['img'];
      $info['time']=date('Y-m-d H:i:s');
      $true=M('xldd_comment')->add($info);
      if($true){
        $pid=M('xldd_comment')->getLastInsId(); //获取最新id
        $data['pid']=$pid;

        $wh['id']=$_POST['id'];
        $val=array(
          'comments'=>array('exp','comments+1'),
        );
        M('d_mentality')->where($wh)->save($val);//增加评论次数

        $data['studentname']=$_POST['studentname'];
        $data['content']=$_POST['content'];
        $data['time']=date('Y-m-d H:i:s');
        $data['touxiang']=$_POST['img'];
        $this->apiReturn(100,'读取成功',$data); 
      }
   }

   /*心理健康 心理定律导读 获取评论回复*/
   public function getreply(){
      $info['cid']=$_POST['pid'];
      $data=M('xldd_reply')->where($info)->order('id desc')->select();
      $this->apiReturn(100,'读取成功',$data); 
   }

   /*评论回复*/
   public function reply(){
      $ctitle['id']=$_POST['cid'];
      $ctitles=M('xldd_comment')->where($ctitle)->find();

      $info['ctitle']=$ctitles['mtitle'];
      $info['pid']=$ctitles['studentid'];

      $info['cid']=$_POST['cid'];
      $info['reply']=$_POST['reply'];
      $info['studentid']=$_POST['studentid'];
      $info['studentname']=$_POST['studentname'];
      $info['touxiang']=$_POST['img'];
      $info['time']=date('Y-m-d H:i:s');
      $where['id']=$_POST['cid'];
      $comment=M('xldd_comment');
      $reply=M('xldd_reply');
      $comment->startTrans();
      $ture1=$reply->add($info);
      $val=array(
          'comment'=>array('exp','comment+1'),
      );
      $ture2=$comment->where($where)->save($val);
      if($ture1 && $ture2){
          $comment->commit();
          $data['studentname']=$_POST['studentname'];
          $data['touxiang']=$_POST['img'];
          $data['reply']=$_POST['reply'];
          $data['time']=date('Y-m-d H:i:s');
          $count=$comment->where($where)->find();
          $data['comment']=$count['comment'];
          $this->apiReturn(100,'读取成功',$data); 
      }else{
          $comment->rollback();
          $this->apiReturn(0,'读取成功',$ctitles); 
      }
   }

   /*点赞*/
   public function dianzan(){
      $which['id']=$_POST['pid'];
      $where['pid']=$_POST['pid'];
      $where['studentid']=$_POST['studentid'];
      $info['pid']=$_POST['pid'];
      $info['studentid']=$_POST['studentid'];
      $info['studentname']=$_POST['studentname'];
      $info['time']=date('Y-m-d H:i:s');
      $true=M('xldd_dianzan')->where($where)->find();
      if($true){ //已点赞
          M('xldd_dianzan')->where($where)->delete();
          $val=array(
              'likes'=>array('exp','likes-1'),
          );
          M('xldd_comment')->where($which)->save($val);
          $count=M('xldd_comment')->where($which)->find();
          $data['num']=$count['likes'];
          $data['color']="#555555";
          $this->apiReturn(100,'读取成功',$data); 
      }else{ //无点赞记录
          M('xldd_dianzan')->add($info);
          $val=array(
              'likes'=>array('exp','likes+1'),
          );
          M('xldd_comment')->where($which)->save($val);
          $count=M('xldd_comment')->where($which)->find();
          $data['num']=$count['likes'];
          $data['color']="#008CD9";
          $this->apiReturn(100,'读取成功',$data); 
      }
   }

    /*榜样力量 列表*/
    public function bang_list(){
      $where['class'] = 2;
      $where['kind'] = 1;
      $info = M('d_mentality')->where($where)
      ->field('id,name,looks,comments,time,pict')->order('time desc')->select();
      $this->apiReturn(100,'读取成功',$info); 
    }
}

