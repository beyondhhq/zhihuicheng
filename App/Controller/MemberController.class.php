<?php
namespace App\Controller;
use Think\Controller;
class MemberController extends DomainController {
	/*我的测评*/
    public function wodeceping(){
        $studentid=$_POST['studentid'];

        $where1['studentid']=$studentid;
        $where1['huolande']=array('neq','');
        $huolande=M('student_test_result')->where($where1)->order('id asc')->select();

        $where2['studentid']=$studentid;
        $where2['mbti']=array('neq','');
        $mbti=M('student_test_result')->where($where2)->order('id asc')->select();

        $where4['studentid']=$studentid;
        $where4['xueke']=array('neq','');
        $xueke=M('student_test_result')->where($where4)->order('id asc')->select();

        $wherestudentid['studentid'] = $studentid;
        $zonghe= M('student_test_zonghe')->where($wherestudentid)->select();

        $data=array(
            'max'=>'3',
            'huolande'=>$huolande,
            'mbti'=>$mbti,
            'xueke'=>$xueke,
            'zonghe'=>$zonghe
        );
        
        $this->apiReturn(100,'读取成功',$data);
    }
    /*我的成长档案*/
    public function wodechengzhang(){

        //查询是否填写综合素质档案
        $dangan['studentid']=$_POST['studentid'];
        $dangantrue=M('da_chengzhangdangan')->where($dangan)->select();
        if($dangantrue){
            $data=$dangantrue;
        }else{
            $data=array();
        }
        $this->apiReturn(100,'读取成功',$data);

    }
    /*发送成长档案到教师*/
    public function sendchengzhang(){
        $cid=$_POST['cid'];
        $sid=$_POST['studentid']; 
        $tid=$_POST['teacherid'];  
        $which['id']=$cid; 
        $true=M('da_chengzhangdangan')->where($which)->find();
        if($true){
            $infos['teacherid']=$tid;
            $m=M('da_chengzhangdangan')->where($which)->save($infos);
            if($m){
               $data='1';
              $this->apiReturn(100,'发送成功',$data);

            }else{
               $data='0';
              $this->apiReturn(0,'发送失败',$data);  
            }
            

        }else{
            $data='0';
            $this->apiReturn(0,'查询失败',$data); 
        }

    }
    /*我的陈述报告*/
    public function chenshubaogao(){

        //查询是否填写综合素质档案
        $dangan['studentid']=$_POST['studentid'];
        $dangantrue=M('ziwochenshu')->where($dangan)->select();
        if($dangantrue){
            $data=$dangantrue;
        }else{
            $data=array();
        }
        $this->apiReturn(100,'读取成功',$data);

    }
    /*我的陈述报告详情*/
    public function chenshubaogaocon(){

        $id=$_POST['cid'];
        $where['id']=$id;
        $jilu=M('ziwochenshu')->where($where)->field('id,studentid,studentname,gradename,title as handline,classname,time,status,content')->find();
        $comment['zid']=$id;
        $commenture=M('comment_ziwochenshu')->where($comment)->order('id DESC')->select();
        $data['info']['newjson']=$jilu;
        $data['comment']=$commenture;
        $this->apiReturn(100,'读取成功',$data);

    }
    /*发送陈述报告到教师*/
         
    public function sendchenshu(){
        $tid=$_POST['teacherid']; 
        $cid=$_POST['cid'];
        $which['id']=$cid; 
        $true=M('ziwochenshu')->where($which)->find();
        if($true){
            $infos['teacherid']=$tid;
            $m=M('ziwochenshu')->where($which)->save($infos);
            if($m){
               $data='1';
              $this->apiReturn(100,'发送成功',$data);

            }else{
               $data='0';
              $this->apiReturn(0,'发送失败',$data);  
            }
            

        }else{
            $data='0';
            $this->apiReturn(0,'查询失败',$data); 
        }

    }
    /*活动记录*/
    public function huodongjilu(){

        //查询是否填写综合素质档案
        $dangan['StudentID']=$_POST['studentid'];
        $dangantrue=M('da_activation_record')->where($dangan)->select();
        foreach($dangantrue as $k=>$v){
           $comment['hid']=$v['id'];
           $commenture=M('comment_huodongjilu')->where($comment)->count();
           $dangantrue[$k]['countcomment']=$commenture;
        }
        if($dangantrue){
            $data=$dangantrue;
        }else{
            $data=array();
        }
        $this->apiReturn(100,'读取成功',$data);

    }
    /*活动记录详情*/
    public function huodongjilucon(){

        $id=$_POST['hid'];
        $where['ID']=$id;
        $jilu=M('da_activation_record')->where($where)->find();
        $json=json_decode($jilu['jsons'],true);
        $jilu['newjson']=$json;
        $comment['hid']=$id;
        $commenture=M('comment_huodongjilu')->where($comment)->order('id DESC')->select();
        $data['info']=$jilu;
        $data['comment']=$commenture;
        $this->apiReturn(100,'读取成功',$data);

    }
    /*发送活动记录到教师*/
    public function sendhuodong(){
        $cid=$_POST['hid'];
        $tid=$_POST['teacherid'];  
        $which['ID']=$cid; 
        $true=M('da_activation_record')->where($which)->find();
        if($true){
            $infos['teacherid']=$tid;
            $m=M('da_activation_record')->where($which)->save($infos);
            if($m){
               $data='1';
              $this->apiReturn(100,'发送成功',$data);

            }else{
               $data='0';
              $this->apiReturn(0,'发送失败',$data);  
            }
            

        }else{
            $data='0';
            $this->apiReturn(0,'查询失败',$data); 
        }

    }
    /*报考方案*/
    public function baokaofangan(){
             $sid=$_POST['studentid'];
             $which['user_id']=$sid;
             $plands=M('d_user_planned')->where($which)->select();//志愿填报记录
             if($plands){
                 foreach ($plands as $k => $v) {
                     $provincename['ProvinceID']=$v['province_id'];
                     $provininfos=M('provinces')->where($provincename)->find();
                     $studentname['StudentID']=$v['user_id'];
                     $studentinfos=M('student')->where($studentname)->find();
                     $batch['id']=$v['batchid'];
                     $batchinfos=M('d_batch')->where($batch)->find();
                     $plands[$k]['province']=$provininfos['provincesname'];
                     $plands[$k]['studentname']=$studentinfos['studentname'];
                     $plands[$k]['studentid']=$studentinfos['studentid'];
                     $plands[$k]['batch']=$batchinfos['name'];
                 }
             }
             $data=$plands;
             $this->apiReturn(100,'操作成功',$data);

    }
    /*报考方案详情*/
    public function baokaofangancon(){
      
        $id = $_POST['bid'];
        $here['planned_id']=$id;
        $ins=M('d_user_planned_univ')->order('univ_seq')->where($here)->select();
        $where['id'] = $_GET['name'];
        $univ = M('d_user_planned')->where($where)->select();
        $bathid['id'] = $univ['batchid'];
        $batch = M('d_batch')->where($batchid)->find();
        $this->assign('batch',$batch);
        $this->assign('ins',$ins);

        //老师评价
        $comment['mid']=$id;
        $commenture=M('comment_monizhiyuan')->where($comment)->order('id DESC')->select();

        $data['batch']=$batch;
        $data['ins']=$ins;
        $data['comment']=$commenture;

        $this->apiReturn(100,'操作成功',$data);

    }

    /*我的笔记*/
    public function wodebiji(){
        $sid=$_POST['studentid'];
        $nianji=$_POST['nianji']?$_POST['nianji']:"全部";
        $kemu=$_POST['kemu']?$_POST['kemu']:"全部";
        $name=$_POST['name']?$_POST['vname']:"";
        if($nianji){
            if($nianji=='全部'){
               if($kemu){
                 if($kemu=='全部'){
                    if($name){
                       $who['t_video_notes.studentid']=$sid;
                       $who['t_video_notes.vname']=array('like',$name);
                    }else{
                       $who['t_video_notes.studentid']=$sid;
                    }
                 }else{
                    if($name){
                       $who['t_video_notes.studentid']=$sid;
                       $who['t_video_notes.kemu']=$kemu; 
                       $who['t_video_notes.vname']=array('like',$name);
                    }else{
                       $who['t_video_notes.studentid']=$sid;
                       $who['t_video_notes.kemu']=$kemu; 
                    }

                 }
               }
            }else{
               if($kemu){
                 if($kemu=='全部'){
                    if($name){
                       $who['t_video_notes.studentid']=$sid;
                       $who['t_video_notes.nianji']=$nianji;
                       $who['t_video_notes.vname']=array('like',$name);
                    }else{
                       $who['t_video_notes.nianji']=$nianji;
                       $who['t_video_notes.studentid']=$sid;
                    }
                 }else{
                    if($name){
                       $who['t_video_notes.studentid']=$sid;
                       $who['t_video_notes.nianji']=$nianji;
                       $who['t_video_notes.kemu']=$kemu; 
                       $who['t_video_notes.vname']=array('like',$name);
                    }else{
                       $who['t_video_notes.studentid']=$sid;
                       $who['t_video_notes.nianji']=$nianji;
                       $who['t_video_notes.kemu']=$kemu; 
                    }

                 }
               }

            }

        }
        $one=M('video_notes')->where($who)->select(); 
        if($one){
            // $count=M('video_notes')->where($who)->count(); //分页,总记录数
            // $Page= new \Think\Page($count,9);
            // $show= $Page->show();//分页,显示输出
            $info=M('video_notes')->where($who)->join('left join t_video_dezhi on t_video_notes.kid=t_video_dezhi.kid')->field('t_video_notes.*,t_video_dezhi.kimage')->select();
            $data=$info;
            $this->apiReturn(100,'操作成功',$data);
        }else{
            $data=0;
            $this->apiReturn(100,'操作成功',$data);
        }
    }
    /*我的笔记详情*/
    public function wodebijicon(){
        $sid=$_POST['studentid'];

        $who['studentid']=$sid;
        $who['_logic'] = 'and';
        $one=M('video_notes')->where($who)->select(); 
        if($one){
            // $count=M('video_notes')->where($who)->count(); //分页,总记录数
            // $Page= new \Think\Page($count,9);
            // $show= $Page->show();//分页,显示输出
            $info=M('video_notes')->where($who)->select();
            $data=$info;
            $this->apiReturn(100,'操作成功',$data);
        }else{
            $data=0;
            $this->apiReturn(100,'操作成功',$data);
        }
    }
    /*我的同学*/
    public function wodetongxue(){
        $studentid=$_POST['studentid'];
        //学生信息
        $student=M('student')->where('studentid='.$studentid)->find();

        $where['ClassID']=$student['classid'];
        $where['StudentID']=array('neq',$studentid);
        $infos =M('student')->where($where)->field('studentname,img')->select();
        
        $clssc['ClassId']=$student['classid'];
        $class =M('class')->where($clssc)->find();

        $data=array(
            'lists'=>$infos,
            'grade'=>$class['grade'],
            'class'=>$class['classname']
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*我的老师*/
    public function teacherinfo(){
        $tid=$_POST['teacherid'];
        //班主任信息
        $teacher=M('teacher')->where('TeacherID='.$tid)->find();
        $data=$teacher;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*关注院校*/
    public function guanzhuyuanxiao(){
        $dastore['studentid']=$_POST['studentid'];
        $dainfo=M('collect')->where($dastore)->select();
        foreach($dainfo as $k=>$v){
            $schoolid.=$v['schoolid'].',';
        }
        if($schoolid){
            $daxue['id']=array('in',$schoolid);
            $daxueinfo=M('d_university')->where($daxue)->field('id,province,dxmc,logo')->select();
        }
        $data=$daxueinfo;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*高考倒计时*/
    public function daojishi(){
        $studentid=$_POST['studentid'];
        //学生信息
        $student=M('student')->where('studentid='.$studentid)->find();
        $cid=$student['cid'];
        /*高考倒计时start*/
        $banji['ClassId']=$cid;
        $banji=M('class')->where($banji)->find();
        $nianji=$banji['grade']; //高几
        $activa['id']=$cid;
        $activa=M('activation')->where($activa)->find();
        $actime=strtotime($activa['actime']);
        $actimeY=date("Y",$actime); //激活年份
        $actimeM=date("m",$actime); //激活月份
                // if($nianji=='高一'){
        //  if($actimeM >= 9){
        //      $gaokao=$actimeY+3; //高考年份
        //  }else{
        //      $gaokao=$actimeY+2; //高考年份
        //  }
        // }elseif($nianji=='高二'){
        //  if($actimeM >= 9){
        //      $gaokao=$actimeY+2; //高考年份
        //  }else{
        //      $gaokao=$actimeY+1; //高考年份
        //  }
        // }else{
        //  if($actimeM >= 9){
        //      $gaokao=$actimeY+1; //高考年份
        //  }else{
        //      $gaokao=$actimeY; //高考年份
        //  }
        // }

        $anianji=$student['nianji'];
        $chazhi=$actimeY-$anianji;
        if($chazhi =='0'){
            $gaokao=$actimeY+3;
        }elseif($chazhi =='1'){
            $gaokao=$actimeY+2;
        }else{
            $gaokao=$actimeY+1;
        }
        $this->assign('gaokao',$gaokao);
        $gaokao=$gaokao.'-06-07'; //高考年月日
        $gaokao_list=explode("-",$gaokao);
        $nowtime=date('Y-m-d',time());//今天日期
        $actimeYmd_list=explode("-",$nowtime);
        $day1=mktime(0,0,0,$gaokao_list[1],$gaokao_list[2],$gaokao_list[0]);
        $day2=mktime(0,0,0,$actimeYmd_list[1],$actimeYmd_list[2],$actimeYmd_list[0]);
        $days=round(($day1-$day2)/3600/24);
        if($days < 1){
            $days=0;
        }
        $this->assign('days',$days);
        $data=$days;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*修改密码*/
    public function password(){
        $old['PassWord']=md5($_POST['oldpwd']);
        $who['StudentID']=$_POST['studentid'];
        $data=M('student')->where($who)->find();
        if($old['PassWord'] != $data['password']){
            $data=array(
                'error'=>'原密码错误'
            );
            $this->apiReturn(0,'读取成功',$data);
        }else{
            $dat['PassWord']=md5($_POST['newpwd']);
            M('student')->where($who)->save($dat);
            $data=array(
                'right'=>'修改成功',
                'newpwd'=>$_POST['newpwd']
            );
            $this->apiReturn(100,'读取成功',$data);
        }
    }
    /*编辑资料*/
    public function editinfo(){
        $type=$_POST['type'];
        $studentid=$_POST['studentid'];
        $upload = new \Think\Upload();// 实例化上传类
        $upload->autoSub=false;
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg','png','gif', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Public/Home/images/touxiang/'; // 设置附件上传根目录
        $info   =   $upload->upload();
        if($info){
            foreach($info as $file){
                $touxiang= $file['savepath'].$file['savename'];
            }
            $edit['Img']=$touxiang;
            $edit['Type']=$type;
            $where['StudentID']=$studentid;
            M('student')->where($where)->save($edit);

            $data=array(
                'type'=>$type,
                'touxiang'=>$touxiang
            );
            $this->apiReturn(100,'请求成功',$data);
        }else{
            $edit['Type']=$type;
            $where['StudentID']=$studentid;
            M('student')->where($where)->save($edit);
            $data=array(
                'type'=>$type
            );
            $this->apiReturn(100,'请求成功',$data);
        }   
    }
    /*学科详情*/
    public function xuekecon(){
      
        $id['ID'] = $_POST['xid'];
        
        $info=M('xk_course')->where($id)->find();

        $data=$info;

        $this->apiReturn(100,'请求成功',$data);


    }

    /*意见反馈*/
    public function feedback(){
        $datas['studentid']=$_POST['studentid'];
        $datas['studentname']=$_POST['studentname'];
        $datas['content']=$_POST['content'];
        $datas['time']=date('Y-m-d H:i:s');
        M('student_feedback')->add($datas);
        $data='提交成功';
        $this->apiReturn(100,'提交成功',$data);
    }

}