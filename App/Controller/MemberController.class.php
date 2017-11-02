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
        $data['info']=$jilu;
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
    //我的课表
    public function wodekebiao(){
        $studentid=$_POST['studentid'];
        $classid=$_POST['classid'];
        $cid=$_POST['cid'];exit;
        $banji['ClassId']=$classid;
        $banji=M('class')->where($banji)->find();
        //dump($banji);
        $nianji=$banji['enrollmenttime']; //高几
        $activa['id']=session('cid');
        $activa=M('activation')->where($activa)->find();
        $actime=strtotime($activa['actime']);
        $actimeY=date("Y",$actime); //激活年份
        $actimeM=date("m",$actime); //激活月份
        //dump($activa);
        $where['studentid']=$studentid;
        $student=M('student')->where($where)->find();
        $anianji=$student['nianji'];
        $chazhi=$actimeY-$anianji;
        
        if($chazhi =='0'){
            $gaokao=$actimeY+3;
        }elseif($chazhi =='1'){
            $gaokao=$actimeY+2;
        }else{
            $gaokao=$actimeY+1;
        }

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
        
      
        
    }
    /*创建计划*/
    public function createplan(){
        $id = $_POST['id'];
        $name = $_POST['biaoti'];
        $content = $_POST['neirong'];
        $start = $_POST['begin'];
        $end = $_POST['over'];
        $type = $_POST['type'];
        $status = $_POST['zhuangtai'];
        $studentid = $_POST['studentid'];
        $user = M('student_project');
        if($id){
            $where['id'] = $id;
            $info['name'] = $name;
            $info['content'] = $content;
            $info['studentid'] = $studentid;
            $info['type'] = $type;
            $info['status'] = $status;
            $info['starttime'] = $start;
            $info['endtime'] = $end;
            $user->where($where)->save($info);
        }else{
            $info['name'] = $name;
            $info['content'] = $content;
            $info['studentid'] = $studentid;
            $info['type'] = $type;
            $info['status'] = $status;
            $info['starttime'] = $start;
            $info['endtime'] = $end;
            $info['time'] = date("Y-m-d");
            $user->add($info);
        }
        if($user){
            $data = 'yes';
            $this->apiReturn(100,'提交成功',$data);
        }else{
            $data = 'no';
            $this->apiReturn(0,'提交失败',$data);
        } 
    }
    public function planlist(){
       $studentid=$_POST['studentid'];
       $type=$_POST['type'];
       $where['studentid']=$studentid;
       if($type){
          $where['type']=$type;
       }
       $info=M('student_project')->where($where)->order('id DESC')->select();
       $this->apiReturn(100,'提交成功',$info);
    }
    //计划详情
    public function plandetail(){
        $id = $_POST['id'];
        $where['id'] = $id;
        $data = M('student_project')->where($where)->find();
        $this->apiReturn(100,'提交成功',$data);
    }
    //删除计划
    public function delplan(){
        $id = $_POST['id'];
        $where['id'] = $id;
        $result = M('student_project')->where($where)->delete();
        if($result){
            $data = 1;
            $this->apiReturn(100,'删除成功',$data);
        }else{
            $data = 0;
            $this->apiReturn(0,'删除失败',$data);
        }
        

    }
    /*我的成绩*/
    public function wodechengji(){
        echo "功能待确认";
 
    }
    /*我的小组*/
    public function wodexiaozu(){
        //小组信息查询
        $teamwhere['studentid'] = $_POST['studentid'];
        $infoteam = M('learn_team_member')->join(array('t_learn_team on t_learn_team.id = t_learn_team_member.teamid'))
                    ->where($teamwhere)->order('t_learn_team.id DESC')->field('t_learn_team.*')->select();
        if($infoteam){
           $data=$infoteam;
           $this->apiReturn(100,'操作成功',$data);

        }else{
           $data=array();
           $this->apiReturn(0,'操作失败',$data);
        }
    }
    /*我的小组详情*/
    public function xiaozucon(){
        $zuid=$_POST['id'];
        $where['t_learn_team.id']=$zuid;
        $res=M('learn_team')->join('left join t_learn_team_member on t_learn_team.id=t_learn_team_member.teamid left join t_student on t_learn_team_member.studentid=t_student.StudentID')->where($where)->field('t_learn_team.*,t_student.StudentName')->select();
        if($res){
           $data=$res;
           $this->apiReturn(100,'操作成功',$data);

        }else{
           $data=array();
           $this->apiReturn(0,'操作失败',$data);
        }
    }
    /*关注专业*/
    public function guanzhuzhuanye(){
        echo "功能待确认";
 
    }
    /*教师评价列表*/
    public function jiaoshipingjia(){
        $student['studentid'] = $_POST['studentid'];
        $type = isset($_POST['type']) ? $_POST['type'] : '测试结果';
        
        if(!empty($student)){
            switch($type){
                case "测试结果":
                    $where['t_student_test_zonghe.studentid'] = $student['studentid'];
                    $model = M('student_test_zonghe');
                    $data  = $model->join('t_comment_zhuanyeqingxiang on t_comment_zhuanyeqingxiang.zid = t_student_test_zonghe.id')
                    ->where($where)->order('t_student_test_zonghe.id DESC')->field('t_student_test_zonghe.*')->select();
                    //dump($where);
                    foreach ($data as $key => $value) {
                        $data[$key]['vname'] = '专业倾向报告';
                    }
                    break;
                case "选科记录":
                    $where['t_xuekebianzu.studentid'] = $student['studentid'];
                    $model = M('xuekebianzu');
                    $data  = $model->join('t_comment_xuankejilu on t_comment_xuankejilu.xid = t_xuekebianzu.id')
                    ->where($where)->order('t_xuekebianzu.id DESC')->field('t_xuekebianzu.*')->select();
                    foreach ($data as $key => $value) {
                        $xuanke[] = $value['xname1'];
                        $xuanke[] = $value['xname2'];
                        $xuanke[] = $value['xname3'];
                        $data[$key]['vname'] = implode(',',array_filter($xuanke));
                    }
                    break;

                case "活动记录":
                    $where['t_da_activation_record.StudentID'] = $student['studentid'];
                    $model = M('da_activation_record');
                    $data  = $model->join('t_comment_huodongjilu on t_comment_huodongjilu.hid = t_da_activation_record.id')
                    ->where($where)->order('t_da_activation_record.id DESC')->field('t_da_activation_record.*')->select();
                    foreach ($data as $key => $value) {
                        $data[$key]['vname'] = $value['handline'];
                    }
                    break;

                case "成长档案":
                    $where['t_da_chengzhangdangan.studentid'] = $student['studentid'];
                    $model = M('da_chengzhangdangan');
                    $data  = $model->join('t_comment_chengzhangdangan on t_comment_chengzhangdangan.cid = t_da_chengzhangdangan.id')
                    ->where($where)->order('t_da_chengzhangdangan.id DESC')->field('t_da_chengzhangdangan.*')->select();
                    foreach ($data as $key => $value) {
                        $data[$key]['vname'] = $value['title'];
                    }

                    break;

                case "量化评价":
                    $where['t_da_lianghuapj.studentid'] = $student['studentid'];
                    $model = M('da_lianghuapj');
                    $data  = $model->join('t_comment_zonghelianghua on t_comment_zonghelianghua.zid = t_da_lianghuapj.id')
                    ->where($where)->order('t_da_lianghuapj.id DESC')->field('t_da_lianghuapj.*')->select();
                    foreach ($data as $key => $value) {
                        $data[$key]['vname'] = $value['title'];
                    }
                    break;

                case "陈述报告":
                    $where['t_ziwochenshu.studentid'] = $student['studentid'];
                    $model = M('ziwochenshu');
                    $data  = $model->join('t_comment_ziwochenshu on t_comment_ziwochenshu.zid = t_ziwochenshu.id')
                    ->where($where)->order('t_ziwochenshu.id DESC')->field('t_ziwochenshu.*')->select();
                    foreach ($data as $key => $value) {
                        $data[$key]['vname'] = $value['title'];
                    }
                    break;

                case "志愿填报":
                    $where['t_d_user_planned.user_id'] = $student['studentid'];
                    $model = M('d_user_planned');
                    $data  = $model->join('t_comment_monizhiyuan on t_comment_monizhiyuan.mid = t_d_user_planned.id')
                    ->where($where)->order('t_d_user_planned.id DESC')->field('t_d_user_planned.*')->select();
                    foreach ($data as $key => $value) {
                        $data[$key]['vname'] = '模拟志愿';
                        $data[$key]['time'] = $value['create_date'];
                    }
                    break;
            }
            foreach($data as $k=>$v){
                $id=$v['id'];
              if(!empty($student)){
                switch($type){
                 case "测试结果":
                    $wherer['zid'] = $id;
                    $dat = M('comment_zhuanyeqingxiang')->where($wherer)->find();
                    $data[$k]['comment']=$dat;
                    break;

                 case "选科记录":
                    $wherer['xid'] = $id;
                    $dat = M('comment_xuankejilu')->where($wherer)->find();
                    $data[$k]['comment']=$dat;
                    break;

                 case "活动记录":
                    $wherer['hid'] = $id;
                    $dat = M('comment_huodongjilu')->where($wherer)->find();
                    $data[$k]['comment']=$dat;
                    break;

                 case "成长档案":
                    $wherer['cid'] = $id;
                    $dat = M('comment_chengzhangdangan')->where($wherer)->find();
                    $data[$k]['comment']=$dat;
                    break;

                 case "量化评价":
                    $wherer['zid'] = $id;
                    $dat = M('comment_zonghelianghua')->where($wherer)->find();
                    $data[$k]['comment']=$dat;
                    break;

                 case "陈述报告":
                    $wherer['zid'] = $id;
                    $dat = M('comment_ziwochenshu')->where($wherer)->find();
                    $data[$k]['comment']=$dat;
                    break;

                 case "志愿填报":
                    $wherer['mid'] = $id;
                    $dat = M('comment_monizhiyuan')->where($wherer)->find();
                    $data[$k]['comment']=$dat;
                    break;
                }
              }

            }
            $this->apiReturn(100,'请求成功',$data);
        }
 
    }
    //教师评价详情
    public function jiaoshipingjiacon(){
        $student = $_POST['studentid'];
        $id=$_POST['id'];
        $type = isset($_POST['type']) ? $_POST['type'] : '测试结果';
        if(!empty($student)){
            switch($type){
                case "测试结果":
                    $where['zid'] = I('post.id');
                    $data = M('comment_zhuanyeqingxiang')->where($where)->find();
                    break;

                case "选科记录":
                    $where['xid'] = I('post.id');
                    $data = M('comment_xuankejilu')->where($where)->find();
                    break;

                case "活动记录":
                    $where['hid'] = I('post.id');
                    $data = M('comment_huodongjilu')->where($where)->find();
                    break;

                case "成长档案":
                    $where['cid'] = I('post.id');
                    $data = M('comment_chengzhangdangan')->where($where)->find();
                    break;

                case "量化评价":
                    $where['zid'] = I('post.id');
                    $data = M('comment_zonghelianghua')->where($where)->find();
                    break;

                case "陈述报告":
                    $where['zid'] = I('post.id');
                    $data = M('comment_ziwochenshu')->where($where)->find();
                    break;

                case "志愿填报":
                    $where['mid'] = I('post.id');
                    $data = M('comment_monizhiyuan')->where($where)->find();
                    break;
            }
        }
        $this->apiReturn(100,'请求成功',$data);

    }
    //个人中心修改信息保存
    public function savememberinfo(){

       $date=$_POST['date'];
       $studentid=$_POST['studentid'];
       $where['StudentID']=$studentid;
       $data['Birthday']=$date;
       $res=M('student')->where($where)->save($data);
       if($res){

         $data="修改成功";
         $this->apiReturn(100,'请求成功',$data);

       }else{

         $data="修改失败";
         $this->apiReturn(0,'请求成功',$data);

       }


    }



}