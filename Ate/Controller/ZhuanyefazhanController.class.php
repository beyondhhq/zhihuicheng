<?php
namespace Ate\Controller;
use Think\Controller;
class ZhuanyefazhanController extends DomainController {
    /*最新文章列表*/
    public function newarticle(){
        $wheres['class']=1; //经验交流或者论文专著文章id
        $wheres['kind']=2; //经验交流或者论文专著文章id
        $jiaoxuenengli=M('teacher_zyfz')->where($wheres)->order('id desc')->field("id,name,time,pict")->limit(3)->select();
        $newjxnl=array();
        foreach($jiaoxuenengli as $k=>$v){
            $v['type']="教学能力";
            $newjxnl[$k]=$v;
        }
        $wheres['class']='10'; //
        $wheres['kind']='1'; //
        $jiaoxuexinli=M('teacher_xsczzd')->where($wheres)->order('id desc')->field("id,name,time,pict")->limit(3)->select();
        $newjxxl=array();
        foreach($jiaoxuexinli as $k=>$v){
            $v['type']="教学心理";
            $newjxxl[$k]=$v;
        }
        $combin=array_merge($newjxnl,$newjxxl);
        $data=$combin;
        $this->apiReturn(100,'读取成功',$data);
        
    }
    /*最新文章详情*/
    public function newarticlecon(){
        $aid=$_POST['aid'];
        $type=$_POST['type'];
        if($type=="教学能力"){
           $wheres['class']=1; //经验交流或者论文专著文章id
           $wheres['kind']=2; //经验交流或者论文专著文章id
           $wheres['id']=$aid;
           $jiaoxuenengli=M('teacher_zyfz')->where($wheres)->find();
           if($jiaoxuenengli){
             $data=$jiaoxuenengli;
             $this->apiReturn(100,'读取成功',$data);
           }else{
             $data['msg']="查看详情失败！";
             $this->apiReturn(0,'读取失败',$data);
           }


        }
        if($type=="教学心理"){
           $wheres['class']=10; //经验交流或者论文专著文章id
           $wheres['kind']=1; //经验交流或者论文专著文章id
           $wheres['id']=$aid;
           $jiaoxuexinli=M('teacher_xsczzd')->where($wheres)->find();
           if($jiaoxuexinli){
             $data=$jiaoxuexinli;
             $this->apiReturn(100,'读取成功',$data);
           }else{
             $data['msg']="查看详情失败！";
             $this->apiReturn(0,'读取失败',$data);
           }
            
        }


    }
    /*学科素养列表*/
    public function xuekesuyang(){
        
        $page=$_POST['page']?$_POST['page']:1;
        $limit=$_POST['limit']?$_POST['limit']:10;
        $showlimit=$limit*$page;
        $wheres['class']=1; //经验交流或者论文专著文章id
        $wheres['kind']=2; //经验交流或者论文专著文章id
        //$count=M('teacher_zyfz')->where($wheres)->count(); //分页,总记录数
        // $comment=M('teacher_zyfz')->where($wheres)->order('id desc')->field("id,name,time,pict")->limit(0,$showlimit)->select();
        $comment=M('teacher_zyfz')->where($wheres)->order('id desc')->field("id,name,time,pict")->select();
        $data=$comment;
        // $data['pagination']['page']=$page;
        // $data['pagination']['limit']=$limit;
        // $data['pagination']['count']=$count;
        $this->apiReturn(100,'读取成功',$data);

    }
    /*学科素养详情*/
    public function xuekesuyangcon(){
        //学科素养文章id
        $id=$_POST['aid'];
        $wheres['id']=$id;
        $wheres['class']=1;
        $wheres['kind']=2; 
        $res=M('teacher_zyfz')->where($wheres)->find();
        if($res){
          $data=$res;
          $this->apiReturn(100,'读取成功',$data);
        }else{
          $data="";
          $this->apiReturn(0,'读取失败',$data);
        }


    }
    /*教学技术视频列表加内容*/
    public function jiaoxuejishuvedio(){
       // 教学视频详情
        $id=$_POST['flvid'];
        if($id){
          $where['id']=$id;
          $where['category']='1';
          $flv=M('teacher_flv')->where($where)->find();
        }else{
          $where['id']='1';
          $where['category']='1';
          $flv=M('teacher_flv')->where($where)->find();
        }
        // 教学视频标题列表
        $which['category']='1';
        $listflv=M('teacher_flv')->where($which)->order('id asc')->select();
        $data['list']=$listflv;
        $data['vedio']=$flv;
        $this->apiReturn(100,'读取成功',$data);

    }
    /*教学技术文章列表*/
    public function jiaoxuejishu(){
        
        //教学技术文章
        // $page=$_POST['page']?$_POST['page']:1;
        // $limit=$_POST['limit']?$_POST['limit']:10;
        // $showlimit=$limit*$page;
        $wheres['class']=2; //经验交流或者论文专著文章id
        $wheres['kind']=2; //经验交流或者论文专著文章id
        //$count=M('teacher_zyfz')->where($wheres)->count(); //分页,总记录数
        // $comment=M('teacher_zyfz')->where($wheres)->order('id desc')->field("id,name,time,pict")->limit(0,$showlimit)->select();
        $comment=M('teacher_zyfz')->where($wheres)->order('id desc')->field("id,name,time,pict")->select();
        $data=$comment;
        $this->apiReturn(100,'读取成功',$data);


        
    }
    /*教学技术详情*/
    public function jiaoxuejishucon(){
        //教学技术文章id
        $id=$_POST['aid'];
        $wheres['id']=$id;
        $wheres['class']=2;
        $wheres['kind']=2; 
        $res=M('teacher_zyfz')->where($wheres)->find();
        if($res){
          $data=$res;
          $this->apiReturn(100,'读取成功',$data);
        }else{
          $data="";
          $this->apiReturn(0,'读取失败',$data);
        }
        
    }
    /*论文专著列表*/
    public function lunwenzhuanzhu(){
            //论文专著
        // $page=$_POST['page']?$_POST['page']:1;
        // $limit=$_POST['limit']?$_POST['limit']:10;
        // $showlimit=$limit*$page;
        $wheres['class']=9; //经验交流或者论文专著文章id
        $wheres['kind']=1; //经验交流或者论文专著文章id
        //$count=M('teacher_xsczzd')->where($wheres)->count(); //分页,总记录数
        // $comment=M('teacher_xsczzd')->where($wheres)->order('id desc')->field("id,name,time,pict")->limit(0,$showlimit)->select();
        $comment=M('teacher_xsczzd')->where($wheres)->order('id desc')->field("id,name,time,pict")->select();
        $data=$comment;
        $this->apiReturn(100,'读取成功',$data);

        
    }
    /*论文专著详情*/
    public function lunwenzhuanzhucon(){
       
        $id=$_POST['aid'];
        $wheres['id']=$id;
        $wheres['class']=9;
        $wheres['kind']=1; 
        $res=M('teacher_xsczzd')->where($wheres)->find();
        if($res){
          $data=$res;
          $this->apiReturn(100,'读取成功',$data);
        }else{
          $data="";
          $this->apiReturn(0,'读取失败',$data);
        }
        
    }
    /*教学心理规律列表*/
    public function jiaoxuexinliguilv(){
             //论文专著
        // $page=$_POST['page']?$_POST['page']:1;
        // $limit=$_POST['limit']?$_POST['limit']:10;
        // $showlimit=$limit*$page;
        $wheres['class']=10; //经验交流或者论文专著文章id
        $wheres['kind']=1; //经验交流或者论文专著文章id
        //$count=M('teacher_xsczzd')->where($wheres)->count(); //分页,总记录数
        // $comment=M('teacher_xsczzd')->where($wheres)->order('id desc')->field("id,name,time,pict")->limit(0,$showlimit)->select();
        $comment=M('teacher_xsczzd')->where($wheres)->order('id desc')->field("id,name,time,pict")->select();
        $data=$comment;
        $this->apiReturn(100,'读取成功',$data);
        
    }
    /*教学心理规律详情*/
    public function jiaoxuexinliguilvcon(){
       
        $id=$_POST['aid'];
        $wheres['id']=$id;
        $wheres['class']=10;
        $wheres['kind']=1; 
        $res=M('teacher_xsczzd')->where($wheres)->find();
        if($res){
          $data=$res;
          $this->apiReturn(100,'读取成功',$data);
        }else{
          $data="";
          $this->apiReturn(0,'读取失败',$data);
        }
        
    }
    /*教学心理指导列表*/
    public function jiaoxuexinlizd(){
       $type=M('teacher_xlzd')->field('type')->group('type')->select();
       $arr['2']="青少年心理";
       $arr['3']="情绪调节";
       $arr['4']="教学心理技术";
       //$arr['5']="xxxxxxx";
       $res=array();
       foreach($type as $k=>$v){
           $res[$k]["type"]=$arr[$v['type']];
           $where['type']=$v['type'];
           $list=M('teacher_xlzd')->where($where)->field('id,name,pict')->select();
           $res[$k]['list']=$list;
       }
       $data=$res;
       $this->apiReturn(100,'读取成功',$data);

    }
    /*教学心理指导详情加评论详情*/
    public function jiaoxuexinlizdcon(){
       $id=$_POST['aid'];
       $where['id']=$id;
       $res=M('teacher_xlzd')->where($where)->find();
       $wher['xid']=$id;
       $com=M('xlzd_comment')->where($wher)->select();
       $data['content']=$res;
       $data['comment']=$com;
       $this->apiReturn(100,'读取成功',$data);

    }
    /*教学心理指导发评论*/
    public function jiaoxuexinlizdcomment(){
       $id=$_POST['aid'];
       $info['xid']=$id;
      //$info['jtitle']=$_POST['ztitle'];
       $info['content']=$_POST['content'];
       $info['teacherid']=$_POST['teacherid'];
       $info['teachername']=$_POST['teachername'];
       $info['touxiang']=$_POST['touxiang'];
       $info['time']=date('Y-m-d H:i:s');
       $true=M('xlzd_comment')->add($info);
      if($true){
        $pid=M('xlzd_comment')->getLastInsId(); //获取最新id
        $data['pid']=$pid;
        $data['teachername']=$_POST['teachername'];;
        $data['content']=$_POST['content'];
        $data['time']=date('Y-m-d H:i:s');
        $data['touxiang']=$_POST['touxiang'];
        $this->apiReturn(100,'评论成功',$data);
      }else{
         $data="";
         $this->apiReturn(0,'评论失败',$data);
      }
    }
    /*经验分享文章列表*/
    public function jinyanfenxiang(){
        //经验分享文章
        // $page=$_POST['page']?$_POST['page']:1;
        // $limit=$_POST['limit']?$_POST['limit']:10;
        // $showlimit=$limit*$page;
        $wheres['class']=2; //经验交流或者论文专著文章id
        $wheres['kind']=5; //经验交流或者论文专著文章id
        //$count=M('teacher_xsczzd')->where($wheres)->count(); //分页,总记录数
        // $comment=M('teacher_xsczzd')->where($wheres)->order('id desc')->field("id,name,time,pict")->limit(0,$showlimit)->select();
        $comment=M('teacher_zyfz')->where($wheres)->order('id desc')->field("id,name,time,pict")->select();
        $data=$comment;
        $this->apiReturn(100,'读取成功',$data);


    }
    /*经验分享文章详情*/
    public function jinyanfenxiangcon(){
        //教学技术文章id
        $id=$_POST['aid'];
        $wheres['id']=$id;
        $wheres['class']=2;
        $wheres['kind']=5; 
        $res=M('teacher_zyfz')->where($wheres)->find();
        if($res){
          $data=$res;
          $this->apiReturn(100,'读取成功',$data);
        }else{
          $data="";
          $this->apiReturn(0,'读取失败',$data);
        }


    }
    /*教师培训列表*/
    public function jiaoshitrain(){

        $where1['category']='2';
        $where1['type']='职业生涯教育';
        $info1=M('teacher_flv')->where($where1)->limit(2)->select();

        $where2['category']='2';
        $where2['type']='职业道德规范';
        $info2=M('teacher_flv')->where($where2)->limit(2)->select();

        $where3['category']='2';
        $where3['type']='圣贤教育智慧';
        $info3=M('teacher_flv')->where($where3)->limit(2)->select();

        $where4['category']='2';
        $where4['type']='信息技术应用';
        $info4=M('teacher_flv')->where($where4)->limit(2)->select();
        
        $arr['shengyajiaoyu']=$info1;
        $arr['daodeguifan']=$info2;
        $arr['jiaoyuzhihui']=$info3;
        $arr['jishuyingyong']=$info4;
        $data=$arr;
        $this->apiReturn(100,'读取成功',$data);


    }
    /*根据type返回所有视频列表*/
    public function peixunleixing(){
        $type=$_POST['type'];
        $where['category']='2';
        $where['type']=$type;
        $info=M('teacher_flv')->where($where)->select();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }

    /*根据flvid返回视频详情*/
    public function peixunleixingcon(){
        $id=$_POST['flvid'];
        $where['category']='2';
        $where['id']=$id;
        $info=M('teacher_flv')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }


}
