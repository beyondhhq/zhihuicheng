<?php
namespace Ate\Controller;
use Think\Controller;
class MemberController extends DomainController{

    /*修改密码*/
    public function password(){
        $old['password']=md5($_POST['oldpwd']);
        $who['TeacherID']=$_POST['teacherid'];
        $data=M('teacher')->where($who)->find();
        if($old['password'] != $data['password']){
            $data=array(
                'error'=>'原密码错误'
            );
            $this->apiReturn(0,'读取成功',$data);
        }else{
            $dat['PassWord']=md5($_POST['newpwd']);
            $res=M('teacher')->where($who)->save($dat);
            if($res){
               $data=array(
                'right'=>'修改成功',
                'newpwd'=>$_POST['newpwd']
               );
            }else{
                $data=array(
                'right'=>'修改失败',
                'newpwd'=>$_POST['newpwd']
               );
            }
            
            $this->apiReturn(100,'读取成功',$data);
        }
    }
    /*测试结果*/
    public function teacher_parent(){

        $where['teacherid']=$_POST['teacherid'];
        $studentname=$_GET['studentname'];
        if($studentname !=''){
            $where['studentname']=array("like","%{$_GET['studentname']}%");
            $this->assign('studentname',$studentname);
        }
        //$count=M('student_test_zonghe')->where($where)->count(); //分页,总记录数
        //$data=M('student_test_zonghe')->where($where)->field('id,studentid,studentname,gradename,classname,time,status,numbers')->order('time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $data=M('student_test_zonghe')->where($where)->field('id,studentid,studentname,gradename,classname,time,status,numbers')->order('status asc,time desc')->select();
        
        foreach ($data as $k => $v) {
            $wh['StudentID']=$v['studentid'];
            $studentinfos=M('student')->where($wh)->find();
            $data[$k]['years']=$studentinfos['nianji'];

            $data[$k]['time']=date('Y-m-d',strtotime($v['time']));
        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*测试结果详情*/
    public function teacher_parentcon(){
        $studentid=$_POST['studentid'];
        $student=M('student')->where('studentid='.$studentid)->find();
        //次数
        $number = $_POST['num'];
        $who['numbers'] = $number;
        $who['studentid']=$studentid;
        $info=M('student_test_result')->where($who)->order('id desc')->select();
        foreach($info as $k=>$v){
            if(!empty($v['huolande'])){
                $huolande = $v['huolande'];
            }
            if(!empty($v['mbti'])){
                $mbti = $v['mbti'];
            }
        }


        $huolande = explode(',',$huolande);
        $huolande=array_merge($huolande); //重建索引
       
        
        $w['numbers']=$number;
        $w['studentid']=$student['studentid'];
        $neirong=M('student_test_zonghe')->where($w)->find();
        $types=$neirong['hollander'];

        $h['type']=$types;
        $zhiyec=M('zonghebaogao_infos')->where($h)->group('zhiye')->select();
        foreach ($zhiyec as $k => $v) {
            $zhiyes .= $v['zhiye']."&nbsp&nbsp&nbsp";
        }


        $y['type']=$types;
        $zhuanyey=M('zonghebaogao_infos')->where($y)->group('zhuanye')->select();
        foreach ($zhuanyey as $k => $v) {
            $zhuanyeinfo .= $v['zhuanye']."&nbsp&nbsp&nbsp";
        }
        //匹配专业
        $sql=M('student_test_zonghe');
        $zhuanye=$sql->where($who)->find(); 

        //学校
        $school['SchoolID'] =$student['schoolid'];
        $school = M('school')->where($school)->find();
        //班级
        $class['ClassId'] = $student['classid'];
        $class =M('class')->where($class)->find();
        //老师评价
        $comment['zid']=$_POST['zid'];
        $commenture=M('comment_zhuanyeqingxiang')->where($comment)->order('id DESC')->select();
        
        if($commenture){
            $this->assign('commenture',$commenture);
        }

        //霍兰德职业新
        $crwhe['Code']=array('like',"%{$huolande[0]}%");
        $chldinfo=M('cp_job_new')->where($crwhe)->select();
        if($chldinfo){
            foreach($chldinfo as $k=>$v){
                $chldinfo[$k]=$v['jobname'];
            }
            $chldinfo=implode(',',$chldinfo);
            $this->assign('chldinfo',$chldinfo);
        }else{
            $one11['H_AnswerCode']=$H_AIDs[0];
            $inf11=M('hollander_answertype')->where($one11)->find();
            $cod1=$inf11['h_profession'];//第一个字母职业
            $one22['H_AnswerCode']=$H_AIDs[1];
            $inf22=M('hollander_answertype')->where($one22)->find();
            $cod2=$inf22['h_profession'];//第二个字母职业
            $one33['H_AnswerCode']=$H_AIDs[2];
            $inf33=M('hollander_answertype')->where($one33)->find();
            $cod3=$inf33['h_profession'];//第三个字母职业
            $hldinfo=$cod1.'、'.$cod2.'、'.$cod3;
            $this->assign('chldinfo',$chldinfo);
        }
        $data['school']=$school;
        $data['zhuanye']=$zhuanye;
        $data['hldinfo']=$hldinfo;
        $data['info2']=$info2;
        $data['duoinfo']=$duoinfo;
        $data['chldinfo']=$chldinfo;
        $data['zhiyes']=$zhiyes;
        $data['zhuanyeinfo']=$zhuanyeinfo;        
        $data['commenture']=$commenture;
        $this->apiReturn(100,'读取成功',$data);
        
    }
    /*发表评论功能接口*/
    public function resultpinglun(){
            $c_id=$_POST['zid'];
      //$info['jtitle']=$_POST['ztitle'];
            $where['id']=$c_id;
            $infos=M('student_test_zonghe')->where($where)->find();
            $comment['zid']=$c_id;
            $comment['studentid']=$infos['studentid'];
            $comment['studentname']=$infos['studentname'];
            $comment['num']=$infos['numbers'];
            $comment['teacherid']=$_POST['teacherid'];
            $comment['teachername']=$_POST['teachername'];
            $comment['touxiang']=$_POST['touxiang'];
            $comment['content']=$_POST['content'];
            $comment['time']=date('Y-m-d H:i:s');
            $true=M('comment_zhuanyeqingxiang')->add($comment);
            if($true){
                $which['status']='1';//状态 已评
                M('student_test_zonghe')->where($where)->save($which);
                $data='1';
                $this->apiReturn(100,'评论成功',$data);
            }else{
                $data='0';
                $this->apiReturn(100,'评论失败',$data);

            }
    }
    /*选科记录*/
    public function teacher_xuankejilu(){

        $where['teacherid']=$_POST['teacherid'];

        $studentname=$_GET['studentname'];
        if($studentname !=''){
            $where['studentname']=array("like","%{$_GET['studentname']}%");
            $this->assign('studentname',$studentname);
        }
        // $count=M('xuekebianzu')->where($where)->count(); //分页,总记录数
        // $Page= new \Think\Page($count,5);
        // $show= $Page->show();//分页,显示输出
        $data=M('xuekebianzu')->where($where)->field('id,studentid,studentname,gradename,classname,time,status,xid1,xname1,xid2,xname2,xid3,xname3')->order('time desc')->select();
        $this->apiReturn(100,'读取成功',$data);
    }
    /*选科记录详情*/
    public function teacher_xuankejilucon(){
        $id=$_POST['xid'];
        $where['id']=$id;
        $jilu=M('xuekebianzu')->where($where)->field('id,studentid,studentname,gradename,classname,time,status,xid1,xname1,xid2,xname2,xid3,xname3')->find();
        $comment['xid']=$id;
        $commenture=M('comment_xuankejilu')->where($comment)->order('id DESC')->select();

        $data['jilu']=$jilu;
        $data['comment']=$commenture;
        $this->apiReturn(100,'读取成功',$data);

    }
    /*选科记录评论*/
    public function xuankejilupinglun(){ 
            $c_id=$_POST['xid'];
      //$info['jtitle']=$_POST['ztitle'];
            $where['id']=$c_id;
            $infos=M('xuekebianzu')->where($where)->find();
            $comment['xid']=$c_id;
            $comment['studentid']=$infos['studentid'];
            $comment['studentname']=$infos['studentname'];
            $comment['teacherid']=$_POST['teacherid'];
            $comment['teachername']=$_POST['teachername'];
            $comment['touxiang']=$_POST['touxiang'];
            $comment['content']=$_POST['content'];
            $comment['time']=date('Y-m-d H:i:s');
            $true=M('comment_xuankejilu')->add($comment);
            if($true){
                $which['status']='1';//状态 已评
                M('xuekebianzu')->where($where)->save($which);
                $data='1';
                $this->apiReturn(100,'评论成功',$data);
            }else{
                $data='0';
                $this->apiReturn(100,'评论失败',$data);

            }
        
    }
    /*活动记录列表*/
    public function teacher_huodongjilu(){
        

        $where['teacherid']=$_POST['teacherid'];

        $studentname=$_GET['studentname'];
        if($studentname !=''){
            $where['studentname']=array("like","%{$_GET['studentname']}%");
            $this->assign('studentname',$studentname);
        }
        // $count=M('da_activation_record')->where($where)->count(); //分页,总记录数
        // $Page= new \Think\Page($count,5);
        // $show= $Page->show();//分页,显示输出
        $res=M('da_activation_record')->where($where)->field('ID,studentid,studentname,gradename,classname,url,time,status,Handline')->order('time desc')->select();
        $data=array();
        foreach($res as $k=>$v){
            $wh['StudentID']=$v['studentid'];
            $img=M('student')->where($wh)->field('img')->find();
            $v['img']=$img['img'];
            $data[$k]=$v;

        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*活动记录详情*/
    public function teacher_huodongjilucon(){
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
    /*活动记录评论*/
    public function huodongpinglun(){
            $c_id=$_POST['hid'];
            $where['ID']=$c_id;
            $infos=M('da_activation_record')->where($where)->find();          
            $comment['hid']=$c_id;
            $comment['studentid']=$infos['studentid'];
            $comment['studentname']=$infos['studentname'];
            $comment['teacherid']=$_POST['teacherid'];
            $comment['teachername']=$_POST['teachername'];
            $comment['touxiang']=$_POST['touxiang'];
            $comment['content']=$_POST['content'];
            $comment['time']=date('Y-m-d H:i:s');
            $true=M('comment_huodongjilu')->add($comment);
            if($true){
                $which['status']='1';//状态 已评
                M('da_activation_record')->where($where)->save($which);
                $data='1';
                $this->apiReturn(100,'评论成功',$data);
            }else{
                $data='0';
                $this->apiReturn(100,'评论失败',$data);

            }

        
    }
    /*成长档案列表*/
    public function teacher_chengzhangdangan(){

        $where['teacherid']=$_POST['teacherid'];

        $studentname=$_GET['studentname'];
        if($studentname !=''){
            $where['studentname']=array("like","%{$_GET['studentname']}%");
            $this->assign('studentname',$studentname);
        }
        // $count=M('da_chengzhangdangan')->where($where)->count(); //分页,总记录数
        // $Page= new \Think\Page($count,10);
        // $show= $Page->show();//分页,显示输出
        $data=M('da_chengzhangdangan')->where($where)->field('id,studentid,studentname,gradename,classname,time,status,url')->order('time desc')->select();
        $this->apiReturn(100,'操作成功',$data);
        
    }
    /*成长档案详情*/
    public function teacher_chengzhangdangancon(){

        $id=$_POST['cid'];
        $where['id']=$id;
        $jilu=M('da_chengzhangdangan')->where($where)->find();
        $comment['cid']=$id;
        $commenture=M('comment_chengzhangdangan')->where($comment)->order('id DESC')->select();
        
        $data['jilu']=$jilu;
        $data['comment']=$commenture;
        $this->apiReturn(100,'读取成功',$data);

    }
    /*成长档案评论*/
    public function chengzhangpinglun(){
            $c_id=$_POST['cid'];
            $where['id']=$c_id;
            $infos=M('da_chengzhangdangan')->where($where)->find();          
            $comment['cid']=$c_id;
            $comment['studentid']=$infos['studentid'];
            $comment['studentname']=$infos['studentname'];
            $comment['teacherid']=$_POST['teacherid'];
            $comment['teachername']=$_POST['teachername'];
            $comment['touxiang']=$_POST['touxiang'];
            $comment['content']=$_POST['content'];
            $comment['time']=date('Y-m-d H:i:s');
            $true=M('comment_chengzhangdangan')->add($comment);
            if($true){
                $which['status']='1';//状态 已评
                M('da_chengzhangdangan')->where($where)->save($which);
                $data='1';
                $this->apiReturn(100,'评论成功',$data);
            }else{
                $data='0';
                $this->apiReturn(100,'评论失败',$data);
            }
    }
    /*量化评价列表*/
    public function teacher_lianghuapingjia(){

        $where['teacherid']=$_POST['teacherid'];

        $studentname=$_GET['studentname'];
        if($studentname !=''){
            $where['studentname']=array("like","%{$_GET['studentname']}%");
            $this->assign('studentname',$studentname);
        }
        // $count=M('da_lianghuapj')->where($where)->count(); //分页,总记录数
        // $Page= new \Think\Page($count,5);
        // $show= $Page->show();//分页,显示输出
        $data=M('da_lianghuapj')->where($where)->field('id,studentid,studentname,gradename,classname,title,time,status,category')->order('time desc')->select();
        $this->apiReturn(100,'操作成功',$data);
    }
    /*量化评价详情*/
    public function teacher_lianghuapingjiacon(){

        $id=$_POST['lid'];
        $where['id']=$id;
        $jilu=M('da_lianghuapj')->where($where)->find();
        $comment['zid']=$id;
        $commenture=M('comment_zonghelianghua')->where($comment)->order('id DESC')->select();
        $data['jilu']=$jilu;
        $data['comment']=$commenture;
        $this->apiReturn(100,'读取成功',$data);

    }
    /*量化评价评论*/
    public function lianghuapinglun(){
            $c_id=$_POST['lid'];
            $where['id']=$c_id;
            $infos=M('da_lianghuapj')->where($where)->find();          
            $comment['zid']=$c_id;
            $comment['studentid']=$infos['studentid'];
            $comment['studentname']=$infos['studentname'];
            $comment['teacherid']=$_POST['teacherid'];
            $comment['teachername']=$_POST['teachername'];
            $comment['touxiang']=$_POST['touxiang'];
            $comment['content']=$_POST['content'];
            $comment['category']=$_POST['category'];
            $comment['time']=date('Y-m-d H:i:s');
            $true=M('comment_zonghelianghua')->add($comment);
            if($true){
                $which['status']='1';//状态 已评
                M('da_lianghuapj')->where($where)->save($which);
                $data='1';
                $this->apiReturn(100,'评论成功',$data);
            }else{
                $data='0';
                $this->apiReturn(100,'评论失败',$data);
            }
    }
    /*陈述报告列表*/
    public function teacher_chenshubaogao(){

        $where['teacherid']=$_POST['teacherid'];

        $studentname=$_GET['studentname'];
        if($studentname !=''){
            $where['studentname']=array("like","%{$_GET['studentname']}%");
            $this->assign('studentname',$studentname);
        }
        // $count=M('ziwochenshu')->where($where)->count(); //分页,总记录数
        // $Page= new \Think\Page($count,5);
        // $show= $Page->show();//分页,显示输出
        $data=M('ziwochenshu')->where($where)->field('id,studentid,studentname,gradename,title as handline,classname,time,status')->order('time desc')->select();
        $this->apiReturn(100,'操作成功',$data);

    }
    /*陈述报告详情*/
    public function teacher_chenshubaogaocon(){
        $id=$_POST['cid'];
        $where['id']=$id;
        $jilu=M('ziwochenshu')->where($where)->field('id,studentid,studentname,gradename,title as handline,classname,time,status,content')->find();
        $comment['zid']=$id;
        $commenture=M('comment_ziwochenshu')->where($comment)->order('id DESC')->select();
        $data['info']=$jilu;
        $data['comment']=$commenture;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*陈述报告评论*/
    public function chenshupinglun(){
            $c_id=$_POST['cid'];
            $where['id']=$c_id;
            $infos=M('ziwochenshu')->where($where)->find();          
            $comment['zid']=$c_id;
            $comment['studentid']=$infos['studentid'];
            $comment['studentname']=$infos['studentname'];
            $comment['teacherid']=$_POST['teacherid'];
            $comment['teachername']=$_POST['teachername'];
            $comment['touxiang']=$_POST['touxiang'];
            $comment['content']=$_POST['content'];
            $comment['time']=date('Y-m-d H:i:s');
            $true=M('comment_ziwochenshu')->add($comment);
            if($true){
                $which['status']='1';//状态 已评
                M('ziwochenshu')->where($where)->save($which);
                $data='1';
                $this->apiReturn(100,'评论成功',$data);
            }else{
                $data='0';
                $this->apiReturn(100,'评论失败',$data);
            }
    }
    /*模拟志愿列表*/
    public function teacher_monizhiyuan(){

        $name=$_GET['studentname'];

        $where['TeacherID']=$_POST['teacherid'];

        $teacherinfo=M('student')->where($where)->select();
        foreach ($teacherinfo as $k => $v) {
            $studentids .=$v['studentid'].','; //学生id
        }
        $which['sendteacher']='1';
        if($name){
             $student['StudentName']=$name;
             $student['TeacherID']=$_POST['teacherid'];
             $students=M('student')->where($student)->find();
             $which['user_id']=$students['studentid'];
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
         }else{
           if($studentids){
             $which['user_id']=array('in',$studentids);
             // $count=M('d_user_planned')->where($which)->count(); //分页,总记录数
             // $Page= new \Think\Page($count,10);
             // $show= $Page->show();//分页,显示输出
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

           }
           $data=$plands;
           $this->apiReturn(100,'操作成功',$data);
         }
    }
    /*模拟志愿详情*/
    public function teacher_monizhiyuancon(){

        $id = $_POST['bid'];
        $plannedWhere['t_d_user_planned.id'] = $id;

        $planned=M('d_user_planned')->join('t_d_batch ON t_d_user_planned.batchid = t_d_batch.id','LEFT')->where($plannedWhere)->field('t_d_user_planned.id,t_d_batch.name,t_d_batch.year,t_d_batch.batch_score,t_d_batch.remark')->select();
       
        $plannedUnivWhere['planned_id']=$planned[0]['id'];
        $plannedUniv=M('d_user_planned_univ')->where($plannedUnivWhere)->select();


        $plannedUnivs=array();
        foreach($plannedUniv as $k=>$v){
            $plannedUnivs[$v[univ_seq]]['univ_seq']=$v[univ_seq];
            $plannedUnivs[$v[univ_seq]]['univ_name']=$v[univ_name];
            $plannedUnivs[$v[univ_seq]]['str']=empty($plannedUnivs[$v[univ_seq]]['major_name'])?'':'、';
            $plannedUnivs[$v[univ_seq]]['major_name'].= $plannedUnivs[$v[univ_seq]]['str'].$v[major_name];
            $plannedUnivs[$v[univ_seq]]['obey']=$v[obey];
        }
        $newplan=array();
        $i=0;
        foreach($plannedUnivs as $k=>$v){
            
            $newplan[$i]=$v;
            $i++;

        }
        //老师评价
        $comment['mid']=$id;
        $commenture=M('comment_monizhiyuan')->where($comment)->order('id DESC')->select();

        $data['planned']=$planned;
        $data['plannedUnivs']=$newplan;
        $data['comment']=$commenture;

        $this->apiReturn(100,'操作成功',$data);
    }
    /*模拟志愿评论*/
    public function monipinglun(){
        
            $c_id=$_POST['bid'];       
            $comment['zid']=$c_id;
            $comment['studentid']=$_POST['studentid'];
            $comment['teacherid']=$_POST['teacherid'];
            $comment['teachername']=$_POST['teachername'];
            $comment['touxiang']=$_POST['touxiang'];
            $comment['content']=$_POST['content'];
            $comment['time']=date('Y-m-d H:i:s');
            $true=M('comment_monizhiyuan')->add($comment);
            if($true){
                $which['status']='1';//状态 已评
                M('ziwochenshu')->where($where)->save($which);
                $data='1';
                $this->apiReturn(100,'评论成功',$data);
            }else{
                $data='0';
                $this->apiReturn(100,'评论失败',$data);
            }


    }
	public function yuanxiaozhuanye(){
		$id = $_GET['name'];
        $plannedWhere['t_d_user_planned.id'] = $id;

        $planned=M('d_user_planned')->join('t_d_batch ON t_d_user_planned.batchid = t_d_batch.id','LEFT')->where($plannedWhere)->field('t_d_user_planned.id,t_d_batch.name,t_d_batch.year,t_d_batch.batch_score,t_d_batch.remark')->select();
       
        $plannedUnivWhere['planned_id']=$planned[0]['id'];
        $plannedUniv=M('d_user_planned_univ')->where($plannedUnivWhere)->select();


        $plannedUnivs=array();
        foreach($plannedUniv as $k=>$v){
            $plannedUnivs[$v[univ_seq]]['univ_seq']=$v[univ_seq];
            $plannedUnivs[$v[univ_seq]]['univ_name']=$v[univ_name];
            $plannedUnivs[$v[univ_seq]]['str']=empty($plannedUnivs[$v[univ_seq]]['major_name'])?'':'、';
            $plannedUnivs[$v[univ_seq]]['major_name'].= $plannedUnivs[$v[univ_seq]]['str'].$v[major_name];
            $plannedUnivs[$v[univ_seq]]['obey']=$v[obey];
        }
        
        //老师评价
        $comment['mid']=$id;
        $commenture=M('comment_monizhiyuan')->where($comment)->order('id DESC')->select();
        if($commenture){
            $this->assign('commenture',$commenture);
        }

        $this->assign('planned',$planned);
        $this->assign('plannedUnivs',$plannedUnivs);

		
		$this->display();
	}
    /*家长信息*/
    public function teacher_jiazhangxinxi(){

        $where['TeacherID']=$_POST['teacherid'];
        $class = M('class')->where($where)->select();
        if(!empty($class)){
            foreach($class as $ck=>$cv){
                $studentwhere[$ck]['ClassID'] = $cv['classid'];
                $studentwhere[$ck]['SchoolID'] = $cv['schoolid'];
                $studentwhere[$ck]['Cid'] = array('neq','');
                $studentinfo[$ck]=M('student')->where($studentwhere[$ck])->select();

                foreach ($studentinfo[$ck] as $k => $v) {
                        $wh[$ck.$k]['parentcard']=$v['parentcard'];
                        $infos[$ck.$k]=M('parent')->where($wh[$ck.$k])->find();
                        if(!empty($infos[$ck.$k])){
                            $info[$ck.$k]['studentname']=$v['studentname'];
                            $info[$ck.$k]['ptouxiang']=$infos[$ck.$k]['touxiang'];
                            $info[$ck.$k]['parentname']=$infos[$ck.$k]['parentname'];
                        }
                }
            }
            $newarr=array();
            $i=0;
            foreach($info as $k=>$v){
               $newarr[$i]=$v;
               $i++;

            }
            $data=$newarr;
        }else{
            $data=array();

        }

        $this->apiReturn(100,'操作成功',$data);

    }
    /*教师圈*/
    public function teacher_quan(){

        $where['TeacherID']=$_POST['teacherid'];
        $teacher=M('teacher')->where($where)->find();
        $which['SchoolID']=$teacher['schoolid'];
        $which['Cid']=array('neq','');
        $info=M('teacher')->where($which)->select();
        $data=$info;
        $this->apiReturn(100,'操作成功',$data);

    }
    /*消息列表接口*/
    public function message(){
        $tid=$_POST['teacherid'];
        $type=$_POST['type'];
        //测试结果
        $where['teacherid']=$tid;
        $wherer['TeacherID']=$tid;
        $wher['teacherid']=$tid;
        $wher['status']=0;
        $ceshijieguo=M('student_test_zonghe')->where($where)->field('id,studentid,studentname,gradename,classname,time,status,numbers')->order('status asc,time desc')->select();
        $countceshijieguo=M('student_test_zonghe')->where($wher)->field('status')->count();
        $xuekejilu=M('xuekebianzu')->where($where)->field('id,studentid,studentname,gradename,classname,time,status,xid1,xname1,xid2,xname2,xid3,xname3')->order('status asc,time desc')->select();
        $countxuekejilu=M('xuekebianzu')->where($wher)->field('status')->count();
        $huodongjilu=M('da_activation_record')->where($where)->field('ID,studentid,studentname,gradename,classname,url,time,status,Handline')->order('status asc,time desc')->select();
        $counthuodongjilu=M('da_activation_record')->where($wher)->field('status')->count();
        $chengzhangjilu=M('da_chengzhangdangan')->where($where)->field('id,studentid,studentname,gradename,classname,time,status,url')->order('status asc,time desc')->select();
        $countchengzhangjilu=M('da_chengzhangdangan')->where($wher)->field('status')->count();
        $lianghuapingjia=M('da_lianghuapj')->where($where)->field('id,studentid,studentname,gradename,classname,title,time,status,category')->order('status asc,time desc')->select();
        $countlianghuapingjia=M('da_lianghuapj')->where($wher)->field('status')->count();
        $chenshubaogao=M('ziwochenshu')->where($where)->field('id,studentid,studentname,gradename,title as handline,classname,time,status')->order('status asc,time desc')->select();
        $countchenshubaogao=M('ziwochenshu')->where($wher)->field('status')->count();
        $teacherinfo=M('student')->where($wherer)->select();
        foreach ($teacherinfo as $k => $v) {
            $studentids .=$v['studentid'].','; //学生id
        }
        if($studentids){
             $which['user_id']=array('in',$studentids);
             $plands=M('d_user_planned')->where($which)->select();//志愿填报记录 
             $countbaogaofangan=M('d_user_planned')->where($which)->count();//志愿填报记录 
         
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
                     $plands[$k]['type']='报考方案';
                 }
             }         
        }
        $baogaofangan=$plands;

        foreach($ceshijieguo as $k=>$v){
             $ceshijieguo[$k]['type']='测试结果';
        }
        foreach($xuekejilu as $k=>$v){
             $xuekejilu[$k]['type']='选科记录';
        }
        foreach($huodongjilu as $k=>$v){
             $huodongjilu[$k]['type']='活动记录';
        }
        foreach($chengzhangjilu as $k=>$v){
             $chengzhangjilu[$k]['type']='成长档案';
        }
        foreach($lianghuapingjia as $k=>$v){
             $lianghuapingjia[$k]['type']='量化评价';
        }
        foreach($chenshubaogao as $k=>$v){
             $chenshubaogao[$k]['type']='陈述报告';
        }
        if($type=='测试结果'){
           $data=$ceshijieguo;        
        }
        if($type=='选科记录'){
           $data=$xuekejilu;
        }
        if($type=='活动记录'){         
           $data=$huodongjilu;
        }
        if($type=='成长档案'){      
           $data=$chengzhangjilu;
        }
        if($type=='陈述报告'){          
           $data=$chenshubaogao;
        }
        if($type=='报考方案'){         
           $data=$baogaofangan;
        }

        $this->apiReturn(100,'操作成功',$data);

    }
    /*返回数量*/
    public function count(){
        $tid=$_POST['teacherid'];
        //测试结果
        $where['teacherid']=$tid;
        $wherer['TeacherID']=$tid;
        $wher['teacherid']=$tid;
        $wher['status']=0;
        $countceshijieguo=M('student_test_zonghe')->where($wher)->field('status')->count();
        $countxuekejilu=M('xuekebianzu')->where($wher)->field('status')->count();
        $counthuodongjilu=M('da_activation_record')->where($wher)->field('status')->count();
        $countchengzhangjilu=M('da_chengzhangdangan')->where($wher)->field('status')->count();
        $countlianghuapingjia=M('da_lianghuapj')->where($wher)->field('status')->count();
        $countchenshubaogao=M('ziwochenshu')->where($wher)->field('status')->count();
        $teacherinfo=M('student')->where($wherer)->select();
        foreach ($teacherinfo as $k => $v) {
            $studentids .=$v['studentid'].','; //学生id
        }
        if($studentids){
             $which['user_id']=array('in',$studentids);
             $plans=M('d_user_planned')->where($which)->count();//志愿填报记录 
                
        }
        $countbaogaofangan=$plans;
        
        $data[0]['type']='测试结果';
        $data[0]['count']=$countceshijieguo;
        $data[1]['type']='选科记录';
        $data[1]['count']=$countxuekejilu;
        $data[2]['type']='活动记录';
        $data[2]['count']=$counthuodongjilu;
        $data[3]['type']='成长档案';
        $data[3]['count']=$countchengzhangjilu;
        $data[4]['type']='陈述报告';
        $data[4]['count']=$countchenshubaogao;
        $data[5]['type']='报考方案';
        $data[5]['count']=$countbaogaofangan;
        foreach($data as $k=>$v){
             if($v['count']==0){
                $data[$k]['count']='暂无最近动态';

             }
        }
        $this->apiReturn(100,'操作成功',$data);

    }
}