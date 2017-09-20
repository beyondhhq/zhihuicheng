<?php
namespace Apa\Controller;
use Think\Controller;
class BbsController extends DomainController {
    /*推荐 家长-家长*/
    public function hotjz(){
        $where['one']='1';
        $data=M('bbs')->where($where)->order('id desc')->limit('5')->select();
        foreach ($data as $k => $v) {
            $data[$k]['time']=date('Y-m-d H:i',strtotime($v['time']));
        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*推荐 家长-教师*/
    public function hotjs(){
        $where['one']='2';
        $data=M('bbs')->where($where)->order('id desc')->limit('5')->select();
        foreach ($data as $k => $v) {
            $data[$k]['time']=date('Y-m-d H:i',strtotime($v['time']));
        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*推荐 家长-学校*/
    public function hotxx(){
        $where['one']='3';
        $data=M('bbs')->where($where)->order('id desc')->limit('5')->select();
        foreach ($data as $k => $v) {
            $data[$k]['time']=date('Y-m-d H:i',strtotime($v['time']));
        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*家长-家长1 家长私语1*/
    public function jiazhangsiyu(){
        $where['one']='1';
        $where['two']='1';
        $where['status']='1';
        $data=M('bbs')->where($where)->order('id desc')->field('id,touxiang,title,time,looks,comments,content')->select();
        foreach ($data as $k => $v) {
            $data[$k]['time']=date('Y-m-d H:i',strtotime($v['time']));
        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*家长-家长1 育子经验2*/
    public function jiazhangyuzi(){
        $where['one']='1';
        $where['two']='2';
        $where['status']='1';
        $data=M('bbs')->where($where)->order('id desc')->field('id,touxiang,title,time,looks,comments,content')->select();
        foreach ($data as $k => $v) {
            $data[$k]['time']=date('Y-m-d H:i',strtotime($v['time']));
        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*家长-家长1 健康美食3*/
    public function jiazhangjiankang(){
        $where['one']='1';
        $where['two']='3';
        $where['status']='1';
        $data=M('bbs')->where($where)->order('id desc')->field('id,touxiang,title,time,looks,comments,content')->select();
        foreach ($data as $k => $v) {
            $data[$k]['time']=date('Y-m-d H:i',strtotime($v['time']));
        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*家长-家长1 活动通知4*/
    public function jiazhanghuodong(){
        $where['one']='1';
        $where['two']='4';
        $where['status']='1';
        $data=M('bbs')->where($where)->order('id desc')->field('id,touxiang,title,time,looks,comments,content')->select();
        foreach ($data as $k => $v) {
            $data[$k]['time']=date('Y-m-d H:i',strtotime($v['time']));
        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*家长-教师2 家长私语1*/
    public function jiaoshisiyu(){
        $where['one']='2';
        $where['two']='1';
        $where['status']='1';
        $data=M('bbs')->where($where)->order('id desc')->field('id,touxiang,title,time,looks,comments,content')->select();
        foreach ($data as $k => $v) {
            $data[$k]['time']=date('Y-m-d H:i',strtotime($v['time']));
        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*家长-教师2 育子经验2*/
    public function jiaoshiyuzi(){
        $where['one']='2';
        $where['two']='2';
        $where['status']='1';
        $data=M('bbs')->where($where)->order('id desc')->field('id,touxiang,title,time,looks,comments,content')->select();
        foreach ($data as $k => $v) {
            $data[$k]['time']=date('Y-m-d H:i',strtotime($v['time']));
        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*家长-教师2 健康美食3*/
    public function jiaoshijiankang(){
        $where['one']='2';
        $where['two']='3';
        $where['status']='1';
        $data=M('bbs')->where($where)->order('id desc')->field('id,touxiang,title,time,looks,comments,content')->select();
        foreach ($data as $k => $v) {
            $data[$k]['time']=date('Y-m-d H:i',strtotime($v['time']));
        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*家长-教师2 活动通知4*/
    public function jiaoshihuodong(){
        $where['one']='2';
        $where['two']='4';
        $where['status']='1';
        $data=M('bbs')->where($where)->order('id desc')->field('id,touxiang,title,time,looks,comments,content')->select();
        foreach ($data as $k => $v) {
            $data[$k]['time']=date('Y-m-d H:i',strtotime($v['time']));
        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*家长-学校3 家长私语1*/
    public function xuexiaosiyu(){
        $where['one']='3';
        $where['two']='1';
        $where['status']='1';
        $data=M('bbs')->where($where)->order('id desc')->field('id,touxiang,title,time,looks,comments,content')->select();
        foreach ($data as $k => $v) {
            $data[$k]['time']=date('Y-m-d H:i',strtotime($v['time']));
        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*家长-学校3 育子经验2*/
    public function xuexiaoyuzi(){
        $where['one']='3';
        $where['two']='2';
        $where['status']='1';
        $data=M('bbs')->where($where)->order('id desc')->field('id,touxiang,title,time,looks,comments,content')->select();
        foreach ($data as $k => $v) {
            $data[$k]['time']=date('Y-m-d H:i',strtotime($v['time']));
        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*家长-学校3 健康美食3*/
    public function xuexiaojiankang(){
        $where['one']='3';
        $where['two']='3';
        $where['status']='1';
        $data=M('bbs')->where($where)->order('id desc')->field('id,touxiang,title,time,looks,comments,content')->select();
        foreach ($data as $k => $v) {
            $data[$k]['time']=date('Y-m-d H:i',strtotime($v['time']));
        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*家长-学校3 活动通知4*/
    public function xuexiaohuodong(){
        $where['one']='3';
        $where['two']='4';
        $where['status']='1';
        $data=M('bbs')->where($where)->order('id desc')->field('id,touxiang,title,time,looks,comments,content')->select();
        foreach ($data as $k => $v) {
            $data[$k]['time']=date('Y-m-d H:i',strtotime($v['time']));
        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*发表帖子*/
    public function addpost(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->autoSub=false;
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->saveName = array('uniqid', array('', true));
        $upload->exts      =     array('jpg','png','gif', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Public/Parent/images/bbs/'; // 设置附件上传根目录
        $info   =   $upload->upload();
        if($info){
            foreach($info as $file){
                $pict .= $file['savepath'].$file['savename'].',';
            }
            $pict=explode(',', $pict);
            $where['pic1']=$pict[0];
            $where['pic2']=$pict[1];
            $where['pic3']=$pict[2];
            $where['pic4']=$pict[3];
            $where['pic5']=$pict[4];
            $where['pic6']=$pict[5];
            $where['pic7']=$pict[6];
            $where['pic8']=$pict[7];
            $where['pic9']=$pict[8];
        }
        $parentid=$_POST['parentid'];
        $parid['parentid']=$parentid;
        $parent=M('parent')->where($parid)->find();

        $one=$_POST['one'];
        $two=$_POST['two'];
        $title=$_POST['title'];
        $content=$_POST['content'];
        
        $where['userid']=$parentid;
        $where['username']=$parent['parentname'];
        $where['touxiang']=$parent['touxiang'];
        $where['title']=$title;
        $where['content']=$content;
        $where['time']=date('Y-m-d H:i:s');
        $where['status']='1';
        $where['one']=$one;
        $where['two']=$two;
        M('bbs')->add($where);
        $data=$info;
        $this->apiReturn(100,'发表成功',$data);
    }
    /*帖子页*/
    public function detail(){
        $id=$_POST['id'];
        $where['id']=$id;
        $val=array(
          'looks'=>array('exp','looks+1'),
        );
        M('bbs')->where($where)->save($val);
        $infos=M('bbs')->where($where)->find();
        $bid['bid']=$id;
        $comments=M('bbs_comment')->where($bid)->order('id asc')->select();
        $data=array(
            'info'=>$infos,
            'list'=>$comments
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*评论帖子*/
    public function comment(){
        $bid=$_POST['id'];
        $content=$_POST['content'];
        $parentid=$_POST['parentid'];

        $parid['parentid']=$parentid;
        $parent=M('parent')->where($parid)->find();

        $where['bid']=$bid;
        $datas['bid']=$bid;
        $datas['content']=$content;
        $count=M('bbs_comment')->where($where)->count(); //总数
        $datas['nums']=$count + 1; //楼层
        $datas['userid']=$parentid;
        $datas['username']=$parent['parentname'];
        $datas['touxiang']=$parent['touxiang'];
        $datas['time']=date('Y-m-d H:i:s');
        $true=M('bbs_comment')->add($datas);
        if($true){
            $pid=M('bbs_comment')->getLastInsId(); //获取最新id
            $val=array(
              'comments'=>array('exp','comments+1'),
            );
            $which['id']=$bid;
            M('bbs')->where($which)->save($val);
            $coms['id']=$pid;
            $data=M('bbs_comment')->where($coms)->find();
            $this->apiReturn(100,'读取成功',$data);
        }
    }
    /*获取评论的回复*/
    public function getreply(){
        $info['cid']=$_POST['id'];
        $data=M('bbs_comment_reply')->where($info)->order('id desc')->select();
        $this->apiReturn(100,'读取成功',$data);
    }
    /*回复评论*/
   public function replys(){
      $cid=$_POST['pid'];
      $content=$_POST['reply'];
      $parentid=$_POST['parentid'];

      $parid['parentid']=$parentid;
      $parent=M('parent')->where($parid)->find();

      $info['cid']=$cid;
      $info['content']=$content;
      $info['userid']=$parentid;
      $info['username']=$parent['parentname'];
      $info['touxiang']=$parent['touxiang'];
      $info['time']=date('Y-m-d H:i:s');
      $where['id']=$cid;
      $comment=M('bbs_comment');
      $reply=M('bbs_comment_reply');
      $comment->startTrans();
      $ture1=$reply->add($info);
      $val=array(
          'replys'=>array('exp','replys+1'),
      );
      $ture2=$comment->where($where)->save($val);
      if($ture1 && $ture2){
          $comment->commit();
          $data['username']=$parent['parentname'];
          $data['touxiang']=$parent['touxiang'];
          $data['content']=$content;
          $data['time']=date('Y-m-d H:i:s');
          $count=$comment->where($where)->find();
          $data['replys']=$count['replys'];
          $this->apiReturn(100,'读取成功',$data);
      }else{
          $comment->rollback();
      }
   }

    public function getbwbd()
    {
        $where['class']=$_POST['id'];
        $info=M('parent_bwbd')->where($where)->select();
        $this->apiReturn(100,'读取成功',$info);
    }
}