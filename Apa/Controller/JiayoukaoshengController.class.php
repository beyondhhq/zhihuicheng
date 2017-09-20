<?php
namespace Apa\Controller;
use Think\Controller;
class JiayoukaoshengController extends DomainController {
	/*品德养成*/
    public function pdyc(){
        $sql = M('parent_jyks');
        $where['kind'] = '1';
        $where['class'] = '1';
        $xuekezhidao =$sql->where($where)->field('id,name,time')->order('time desc')->select();
        $data=$xuekezhidao;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*学业指导*/
    public function xyzd(){
        $sql = M('parent_jyks');
        $wherej['kind'] = '1';
        $wherej['class'] = '2';

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
            $wherej['type']='2';
        }else{
            $wherej['type']='1';
        }

        $shengyaguihua = $sql->where($wherej)->field('id,name,time')->order('time desc')->select();
        $data=$shengyaguihua;
        $this->apiReturn(100,'读取成功',$data);
    }
     /*社会实践*/
    public function shsj(){
        $sql = M('parent_jyks');
        $wherej['kind'] = '1';
        $wherej['class'] = '3';
        $shengyaguihua = $sql->where($wherej)->field('id,name,time')->order('time desc')->select();
        $data=$shengyaguihua;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*心理健康*/
    public function xljk(){
        $sql = M('parent_jyks');
        $wherej['kind'] = '2';
        $wherej['class'] = '1';
        $shengyaguihua = $sql->where($wherej)->field('id,name,time')->order('time desc')->select();
        $data=$shengyaguihua;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*健康饮食*/
    public function jkys(){
        $sql = M('parent_jyks');
        $wherej['kind'] = '2';
        $wherej['class'] = '2';
        $shengyaguihua = $sql->where($wherej)->field('id,name,time')->order('time desc')->select();
        $data=$shengyaguihua;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*劳逸结合*/
    public function lyjh(){
        $sql = M('parent_jyks');
        $wherej['kind'] = '2';
        $wherej['class'] = '3';
        $shengyaguihua = $sql->where($wherej)->field('id,name,time')->order('time desc')->select();
        $data=$shengyaguihua;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*社会实践 文章内容页*/
    public function details(){
        $id=$_POST['id'];
        $where['id']=$id;
        $info=M('jilufanli')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*成功之路*/
    public function cgzl(){
        $sql = M('parent_jyks');
        $wherej['kind'] = '3';
        $wherej['class'] = '1';
        $shengyaguihua = $sql->where($wherej)->field('id,name,time')->order('time desc')->select();
        $data=$shengyaguihua;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*前车之鉴*/
    public function qczj(){
        $sql = M('parent_jyks');
        $wherej['kind'] = '3';
        $wherej['class'] = '2';
        $shengyaguihua = $sql->where($wherej)->field('id,name,time')->order('time desc')->select();
        $data=$shengyaguihua;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*习惯养成*/
    public function xgyc(){
        $sql = M('parent_jyks');
        $wherej['kind'] = '3';
        $wherej['class'] = '3';
        $shengyaguihua = $sql->where($wherej)->field('id,name,time')->order('time desc')->select();
        $data=$shengyaguihua;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*12456文章内容页*/
    public function seeinfos(){
        $who['id'] = $_POST['id']; //文章id
        $info = M('parent_jyks')->where($who)->find();
        $where['jid']=$_POST['id']; //文章id
        $comment=M('parent_jyks_comment')->where($where)->order('id desc')->select();
        $data=array(
            'info'=>$info,
            'comment'=>$comment
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*12456 提交文章评论*/
    public function addcomment(){
        $parentid=$_POST['parentid'];
        $where['parentid']=$parentid;
        $parent=M('parent')->where($where)->find();

        $info['jid']=$_POST['id'];
        $info['jtitle']=$_POST['title'];
        $info['content']=$_POST['content'];
        $info['parentid']=$parentid;
        $info['parentname']=$parent['parentname'];
        $info['touxiang']=$parent['touxiang'];
        $info['time']=date('Y-m-d H:i:s');
        $true=M('parent_jyks_comment')->add($info);
        if($true){
            $pid=M('parent_jyks_comment')->getLastInsId(); //获取最新id
            $data['pid']=$pid; //评论id
            $data['parentname']=$parent['parentname'];
            $data['content']=$_POST['content'];
            $data['time']=date('Y-m-d H:i:s');
            $data['touxiang']=$parent['touxiang'];
            $this->apiReturn(100,'读取成功',$data);
        }else{
            $data='评论失败，请重试';
            $this->apiReturn(100,'读取成功',$data);
        }
    }
    /*12456 为评论点赞*/
    public function dianzan(){
        $parentid=$_POST['parentid'];
        $where['parentid']=$parentid;
        $parent=M('parent')->where($where)->find();

        $which['id']=$_POST['pid'];
        $where['pid']=$_POST['pid'];
        $where['parentid']=$parentid;
        $info['pid']=$_POST['pid'];
        $info['parentid']=$parentid;
        $info['parentname']=$parent['parentname'];
        $info['time']=date('Y-m-d H:i:s');
        $true=M('parent_jyks_dianzan')->where($where)->find();
        if($true){ //已点赞
          M('parent_jyks_dianzan')->where($where)->delete();
          $val=array(
              'likes'=>array('exp','likes-1'),
          );
          M('parent_jyks_comment')->where($which)->save($val);
          $count=M('parent_jyks_comment')->where($which)->find();
          $data['likes']=$count['likes'];
          $this->apiReturn(100,'读取成功',$data);
        }else{ //无点赞记录
          M('parent_jyks_dianzan')->add($info);
          $val=array(
              'likes'=>array('exp','likes+1'),
          );
          M('parent_jyks_comment')->where($which)->save($val);
          $count=M('parent_jyks_comment')->where($which)->find();
          $data['likes']=$count['likes'];
          $this->apiReturn(100,'读取成功',$data);
        }
    }
    /*12456 获取评论回复*/
    public function getreply(){
        $info['cid']=$_POST['pid'];
        $data=M('parent_jyks_reply')->where($info)->order('id desc')->select();
        $this->apiReturn(100,'读取成功',$data);
    }
    /*12456 评论回复*/
    public function reply(){
        $parentid=$_POST['parentid'];
        $wheres['parentid']=$parentid;
        $parent=M('parent')->where($wheres)->find();

        $info['cid']=$_POST['pid'];
        $info['reply']=$_POST['reply'];
        $info['parentid']=$parentid;
        $info['parentname']=$parent['parentname'];
        $info['touxiang']=$parent['touxiang'];
        $info['time']=date('Y-m-d H:i:s');
        $where['id']=$_POST['pid'];
        $comment=M('parent_jyks_comment');
        $reply=M('parent_jyks_reply');
        $comment->startTrans();
        $ture1=$reply->add($info);
        $val=array(
          'comment'=>array('exp','comment+1'),
        );
        $ture2=$comment->where($where)->save($val);
        if($ture1 && $ture2){
          $comment->commit();
          $data['parentname']=$parent['parentname'];
          $data['touxiang']=$parent['touxiang'];
          $data['reply']=$_POST['reply'];
          $data['time']=date('Y-m-d H:i:s');
          $count=$comment->where($where)->find();
          $data['comment']=$count['comment'];
          $this->apiReturn(100,'读取成功',$data);
        }else{
          $comment->rollback();
        }
    }
}