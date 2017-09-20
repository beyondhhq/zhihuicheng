<?php
namespace App\Controller;
use Think\Controller;
class YouketangController extends DomainController {
	/*优课堂*/
    public function lists(){
        //$studentid必传
        $studentid=$_POST['studentid'];
        $page=$_POST['page']?$_POST['page']:1;
        $limit=$_POST['limit']?$_POST['limit']:10;
        $grade=$_POST['grade'];
        //var_dump($grade);
        $kemu=$_POST['kemu'];
        //var_dump($kemu);

        $type=$_POST['type'];
        //var_dump($type);

        $banben=$_POST['banben'];
        //var_dump($banben);

        if($grade){
        	if($grade=='不限'){

        	}else{
        	  $where['nianji']=$grade;
        	}
            
        }
        if($kemu){
        	if($kemu=='不限'){

        	}else{
        	  $where['kemu']=$kemu;
        	}
            
        }
        if($type){
        	if($type=='不限'){

        	}else{
        	  $where['category']=$type;
        	}
           
        }
        if($banben){
        	if($banben=='不限'){

        	}else{
        	  $where['banben']=$banben;
        	}

            
        } 
        $who['StudentID']=$studentid;
        $res=M('student')->where($who)->field('nianji')->find();
        $nianji=$res['nianji'];
        $year=date("Y");
        $month=date("m");
        if($year<$nianji){
           return false;
        }else{
           $result=($year-$nianji);
           if($result==0){
              $newgrade="高一";

           }elseif($result==1){
              if($month>9){
                $newgrade='高二';

              }else{
                $newgrade='高一';
 
              }

           }elseif($result==2){
              if($month>9){
                $newgrade='高三';

              }else{
                $newgrade='高二';
 
              }

           }elseif($result==3){
              if($month>6){
                $newgrade='已毕业';

              }else{
                $newgrade='高三';
 
              }

           }


        }
        $gradewhere['nianji']=$newgrade;
        if($_POST){
        	if (empty($grade)&&empty($kemu)&&empty($type)&&empty($banben)) {

               $arr=M('video_dezhi')->where($gradewhere)->order('id asc')->select();
               $pagecount=count($arr);
               $pagination['page']=$page;
               $pagination['limit']=$limit;
               $pagination['pagecount']=$pagecount;

               $newlimit=$page*$limit;

               $data['data']=M('video_dezhi')->where($gradewhere)->order('id asc')->limit(0,$newlimit)->select();
               $data['pagination']=$pagination;
        	
        	}else{
               
        	   $arr=M('video_dezhi')->where($where)->order('id asc')->select();
               $pagecount=count($arr);
               $pagination['page']=$page;
               $pagination['limit']=$limit;
               $pagination['pagecount']=$pagecount;

               $newlimit=$page*$limit;
            
               $data['data']=M('video_dezhi')->where($where)->limit(0,$newlimit)->order('id asc')->select();
               $data['pagination']=$pagination;
        	
        	}
            
        }else{
            $data=array(
                  'msg'=>'请传递studentid'
            	);
        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*优课堂 类型 版本 数组*/
    public function datatypes(){
        $type=array('高中同步课','高考冲刺课');
        $banben=array('通用版','人教版','人教A版','人教B版','教科版','鲁科版','苏教版','北师大版');
        $data=array(
            'leixing'=>$type,
            'banben'=>$banben
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*视频播放页*/
    public function seevideo(){
        $studentid=$_POST['studentid'];
        $kid=$_POST['kid'];
        $paixu=$_POST['paixu'];
        /*增加视频主表浏览量*/
        $zhu['kid']=$kid;
        $zhudata=array(
           'looks' => array('exp','looks+1'),
            );
        M('video_dezhi')->where($zhu)->save($zhudata);
        /*增加视频副表浏览量*/
        $fu['kid']=$kid;
        $fu['paixu']=$paixu;
        $fu['_logic']='and';
        $fudata=array(
           'looks' => array('exp','looks+1'),
            );
        M('video_dezhi_infos')->where($fu)->save($fudata);
        /*获取视频详细信息*/
        $video['kid']=$kid;
        $video['paixu']=$paixu;
        $video['_logic']='and';
        $videoinfos=M('video_dezhi_infos')->where($video)->find();
        /*增加用户浏览视频历史*/
        $user['studentid']=$studentid;
        $user['vid']=$videoinfos['vid'];
        $user['kid']=$kid;
        $user['paixu']=$paixu;
        $user['time']=time();
        M('video_see_log')->add($user);
        /*获取主讲教师信息*/
        $teacher['name']=$videoinfos['teacher'];
        $teacherinfo=M('video_dezhi_teacher')->where($teacher)->find();
        /*获取视频所属集合列表*/
        $vielist['kid']=$kid;
        $vielists=M('video_dezhi_infos')->where($vielist)->select();
        //$this->assign('paixus',$_GET['paixu']); //当前视频项
        /*判断用户是否把视频加入收藏*/
        $store['studentid']=$studentid;
        $store['vid']=$videoinfos['vid'];
        $store['_logic']='and';
        $stores=M('video_stores')->where($store)->find();
        if($stores){
            $stores='1';
        }else{
            $stores='0';
        }
        /*获取用户视频笔记*/
        $note['studentid']=$studentid;
        $note['vid']=$videoinfos['vid'];
        $note['_logic']='and';
        $notes=M('video_notes')->where($note)->find();

        //video
        $url = "http://api.dezhi.com/api/yxke/app-play";
        $datac = array(
            'videoid' => $videoinfos['vid'],
            'account' => '15255441122'
          );
        $datac['hash'] = generateHash($datac,'m3ixljmw6xpy4hd5orzwv1edvm0qbjkz');
        $result = curl($url,$datac);

        //总节数
        $countdata['kid']=$kid;
        $counts=M('video_dezhi_infos')->where($countdata)->group('kid')->count();


        $data=array(
            'content'=>$videoinfos, //获取视频详细信息
            'teacher'=>$teacherinfo,//获取主讲教师信息
            'lists'=>$vielists,//获取视频所属集合列表
            'notes'=>$notes,//获取用户视频笔记
            'stores'=>$stores,//判断用户是否把视频加入收藏
            'video'=>$result, //video
            'counts'=>$counts//总节数
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*优课堂 推荐3条*/
    public function videotj(){
    	$studentid=$_POST['studentid'];
    	$who['StudentID']=$studentid;
        $res=M('student')->where($who)->field('nianji')->find();
        $nianji=$res['nianji'];
        $year=date("Y");
        $month=date("m");
        if($year<$nianji){
           return false;
        }else{
           $result=($year-$nianji);
           if($result==0){
              $newgrade="高一";

           }elseif($result==1){
              if($month>9){
                $newgrade='高二';

              }else{
                $newgrade='高一';
 
              }

           }elseif($result==2){
              if($month>9){
                $newgrade='高三';

              }else{
                $newgrade='高高二';
 
              }

           }elseif($result==3){
              if($month>6){
                $newgrade='已毕业';

              }else{
                $newgrade='高三';
 
              }

           }


        }
        $gradewhere['nianji']=$newgrade;
        $data=M('video_dezhi')->where($gradewhere)->order('id asc')->limit(3)->select();
        $this->apiReturn(100,'读取成功',$data);
    }
    /*优课堂 收藏*/
    public function stores(){
        $studentid=$_POST['studentid'];
        $student=M('student')->where('studentid='.$studentid)->find();

        $store['studentid']=$studentid;
        $store['vid']=$_POST['vid'];
        $store['_logic']='and';
        $stores=M('video_stores')->where($store)->find();
        if($stores){//已收藏
          /*减少视频主表收藏数*/
          $zhu['kid']=$_POST['kid'];
          $zhudata=array(
             'stores' => array('exp','stores-1'),
              );
          M('video_dezhi')->where($zhu)->save($zhudata);
          /*删除用户收藏记录*/
          $udata['vid']=$_POST['vid'];
          $udata['studentid']=$studentid;
          $udata['_logic']='and';
          M('video_stores')->where($udata)->delete();
          $data['infos']='0';
          $this->apiReturn(100,'读取成功',$data);
        }else{//无收藏
          /*增加视频主表收藏数*/
          $zhu['kid']=$_POST['kid'];
          $zhudata=array(
             'stores' => array('exp','stores+1'),
              );
          M('video_dezhi')->where($zhu)->save($zhudata);
          /*用户收藏记录入库*/
          $udata['vid']=$_POST['vid'];
          $udata['kid']=$_POST['kid'];
          $udata['paixu']=$_POST['paixu'];
          $udata['nianji']=$_POST['nianji'];
          $udata['kemu']=$_POST['kemu'];
          $udata['vname']=$_POST['vname'];
          $udata['studentid']=$studentid;
          $udata['studentname']=$student['studentname'];
          $udata['time']=time();
          M('video_stores')->add($udata);
          $data['infos']='1';
          $this->apiReturn(100,'读取成功',$data);
        }
    }
    /*优课堂  笔记*/
    public function notes(){
        $studentid=$_POST['studentid'];
        $student=M('student')->where('studentid='.$studentid)->find();

        $note['studentid']=$studentid;
        $note['vid']=$_POST['vid'];
        $note['_logic']='and';
        $notes=M('video_notes')->where($note)->find();
        if($notes){//有笔记
          $udata['notes']=$_POST['notes'];
          $udata['time']=time();
          M('video_notes')->where($note)->save($udata);
        }else{ //无笔记
          $udata['vid']=$_POST['vid'];
          $udata['kid']=$_POST['kid'];
          $udata['paixu']=$_POST['paixu'];
          $udata['nianji']=$_POST['nianji'];
          $udata['kemu']=$_POST['kemu'];
          $udata['vname']=$_POST['vname'];
          $udata['notes']=$_POST['notes'];
          $udata['studentid']=$studentid;
          $udata['studentname']=$student['studentname'];
          $udata['time']=time();
          M('video_notes')->add($udata);
        }
        $data='保存成功';
        $this->apiReturn(100,'读取成功',$data);
    }
}