<?php
namespace App\Controller;
use Think\Controller;
class MsgController extends DomainController {
	/*动态数量*/
    public function counts(){
        $sid=$_POST['studentid'];
        //专业倾向报告
        $zy['studentid']=$sid;
        $zy['status']='0';
        $zyinfos=M('comment_zhuanyeqingxiang')->where($zy)->count();
        //选科记录
        $xk['studentid']=$sid;
        $xk['status']='0';
        $xkinfos=M('comment_xuankejilu')->where($xk)->count();
        //活动记录
        $hd['studentid']=$sid;
        $hd['status']='0';
        $hdinfos=M('comment_huodongjilu')->where($hd)->count();
        //成长档案
        $cz['studentid']=$sid;
        $cz['status']='0';
        $czinfos=M('comment_chengzhangdangan')->where($cz)->count();
        //综合量化评价
        $zh['studentid']=$sid;
        $zh['status']='0';
        $zhinfos=M('comment_zonghelianghua')->where($zh)->count();
        //自我陈述报告
        $zw['studentid']=$sid;
        $zw['status']='0';
        $zwinfos=M('comment_ziwochenshu')->where($zw)->count();

        $countso=$zyinfos + $xkinfos + $hdinfos + $czinfos + $zhinfos + $zwinfos;
        if($countso==''){
            $countso='0';
        }

        $mj['status']='0';
        $mj['tid']='0';
        $mj['pid']=$sid;
        $mjinfos=M('mjzd_reply')->where($mj)->count();
       

        $xl['status']='0';
        $xl['tid']='0';
        $xl['pid']=$sid;
        $xlinfos=M('xldd_reply')->where($xl)->count();
        $countst=$mjinfos + $xlinfos;
        if($countst==''){
            $countst='0';
        }
        $data['teachercomment']=$countso;
        $data['action']=$countst;
        $this->apiReturn(100,'操作成功',$data);
    }
    /*老师评价*/
    public function mteacher(){
        $sid=$_POST['studentid'];
        $where['StudentID']=$sid;
        $student=M('student')->where($where)->find();
        //专业倾向报告
        $zy['studentid']=$sid;
        $zyinfos=M('comment_zhuanyeqingxiang')->where($zy)->field('*,zid as wid')->order('id desc')->select();
        //选科记录
        $xk['studentid']=$sid;
        $xkinfos=M('comment_xuankejilu')->where($xk)->field('*,xid as wid')->order('id desc')->select();
        //活动记录
        $hd['studentid']=$sid;
        $hdinfos=M('comment_huodongjilu')->where($hd)->field('*,hid as wid')->order('id desc')->select();
        $this->assign('hdinfos',$hdinfos);
        //成长档案
        $cz['studentid']=$sid;
        $czinfos=M('comment_chengzhangdangan')->where($cz)->field('*,cid as wid')->order('id desc')->select();
        $this->assign('czinfos',$czinfos);
        //综合量化评价
        $zh['studentid']=$sid;
        $zhinfos=M('comment_zonghelianghua')->where($zh)->field('*,zid as wid')->order('id desc')->select();
        $this->assign('zhinfos',$zhinfos);
        //自我陈述报告
        $zw['studentid']=$sid;
        $zwinfos=M('comment_ziwochenshu')->where($zw)->field('*,zid as wid')->order('id desc')->select();
        $this->assign('zwinfos',$zwinfos);
        $data['student']=$student;
        $newdata=array();
        if(!empty(zyinfos)){
          foreach($zyinfos as $k=>$v){
            $zyinfos[$k]['type']='专业倾向报告';
          }

        }
        if(!empty(xkinfos)){
          foreach($xkinfos as $k=>$v){
            $xkinfos[$k]['type']='选科记录';
          }
        }
        if(!empty(hdinfos)){
          foreach($hdinfos as $k=>$v){
            $hdinfos[$k]['type']='活动记录';
          }
        }
        if(!empty(czinfos)){
          foreach($czinfos as $k=>$v){
            $czinfos[$k]['type']='成长档案';
          }
        }
        if(!empty(zhinfos)){
          foreach($zhinfos as $k=>$v){
            $zhinfos[$k]['type']='综合量化评价';
          }
        }
        if(!empty(zwinfos)){
          foreach($zwinfos as $k=>$v){
            $zwinfos[$k]['type']='自我陈述报告';
          }
        }
        $b=array_merge($zyinfos,$xkinfos,$hdinfos,$czinfos,$zwinfos);
        $flag = array();  
  
        foreach($b as $v){  
         $flag[] = $v['status'];  
        }  
        array_multisort($flag, SORT_ASC, $b);
        $statuszero=array();
        $statusone=array();
        foreach($b as $k=>$v){
           if($v[status]==0){
              $statuszero[]=$v;
           }
           if($v[status]==1){
              $statusone[]=$v;
           }

        }
        $one = array();  
  
        foreach($statusone as $v){  
         $one[] = $v['time'];  
        }  
        array_multisort($one, SORT_DESC, $statusone);
        $zero = array();  
  
        foreach($statuszero as $v){  
         $zero[] = $v['time'];  
        }  
        array_multisort($zero, SORT_DESC, $statuszero);
        $data=array_merge($statuszero,$statusone);
        $this->apiReturn(100,'操作成功',$data);
    }
    /*点击修改为已读状态*/
    public function changestatus(){
        $id=$_POST['id'];
        $type=$_POST['type'];
        $where['id']=$id;
        if($type=='专业倾向报告'){
          $data['status']=1;
          $m=M('comment_zhuanyeqingxiang')->where($where)->save($data);
        }
        if($type=='选科记录'){
          $data['status']=1;
          $m=M('comment_xuankejilu')->where($where)->save($data);
        }
        if($type=='活动记录'){
          $data['status']=1;
          $m=M('comment_huodongjilu')->where($where)->save($data); 
        }
        if($type=='成长档案'){
          $data['status']=1;
          $m=M('comment_chengzhangdangan')->where($where)->save($data); 
        }
        // if($type=='综合量化评价'){
        //   $data['status']=1;
        //   $m=M('comment_zonghelianghua')->where($where)->save($data);          
        // }
        if($type=='自我陈述报告'){
          $data['status']=1;
          $m=M('comment_ziwochenshu')->where($where)->save($data);
        }        
        if($m){
          $data=1;
          $this->apiReturn(100,'操作成功',$data);
        }else{
          $data=0;
          $this->apiReturn(0,'操作失败',$data);
        }

    }
    /*动态*/
    public function messages(){
        $sid=$_POST['studentid'];
        $where['StudentID']=$sid;
        $student=M('student')->where($where)->find();
        $this->assign('student',$student);

        //名家指导  
        $mjzd['pid']=$sid;
        $mjzd['tid']='0';
        $mjzdinfo=M('mjzd_reply')->where($mjzd)->order('id desc')->select();
        foreach ($mjzdinfo as $k => $v) {
            $com['id']=$v['cid'];
            $comment=M('mjzd_comment')->where($com)->find();
            $mjzdinfo[$k]['mid']=$comment['mid'];
            $mjzdinfo[$k]['type']='mjzd';

        }

        // $mjzdrep['tid']=array('neq','0');
        // $mjzdreply=M('mjzd_reply')->where($mjzdrep)->order('id desc')->select();
        // if($mjzdreply){
        //     $this->assign('mjzdreply',$mjzdreply);
        // }
        //心理定律导读 榜样之路
        $xldd['pid']=$sid;
        $xldd['tid']='0';
        $xlddinfo=M('xldd_reply')->where($xldd)->order('id desc')->select();
        foreach ($xlddinfo as $k => $v) {
            $com['id']=$v['cid'];
            $comment=M('xldd_comment')->where($com)->find();
            $xlddinfo[$k]['mid']=$comment['mid'];
            $xlddinfo[$k]['type']='xldd';

        }
        // $xlddrep['tid']=array('neq','0');
        // $xlddreply=M('xldd_reply')->where($xlddrep)->order('id desc')->select();
        
        //mjzdinfo表示对评论的回复
        //mjzdreply表示对评论回复的回复
        //$data['mjzdreply']=$mjzdreply;
        //xlddinfo表示对评论的回复
     
        //xldd_reply表示对评论的回复的评论
        //$data['xldd_reply']=$xldd_reply;
        $data=array_merge($mjzdinfo,$xlddinfo);
        $this->apiReturn(100,'操作成功',$data);
    }
    /*点击详情修改动态状态*/
    public function changestatu(){
        //$id表示动态id
        $id=$_POST['id'];
        //$type
        $type=$_POST['type'];
        if($type=='mjzd'){
           $where['id']=$id;
           $data['status']=1;
           $m=M('mjzd_reply')->where($where)->save($data);
           if($m){
             $data=1;
             $this->apiReturn(100,'操作成功',$data);
           }else{
             $data=0;
             $this->apiReturn(0,'操作失败',$data);
           }
        }
        if($type=='xldd'){
           $where['id']=$id;
           $data['status']=1;
           $m=M('xldd_reply')->where($where)->save($data);
           if($m){
             $data=1;
             $this->apiReturn(100,'操作成功',$data);
           }else{
             $data=0;
             $this->apiReturn(0,'操作失败',$data);
           }
        }
    }
    /*动态详情*/
    


}