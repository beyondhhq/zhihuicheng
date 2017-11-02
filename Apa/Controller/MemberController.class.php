<?php
namespace Apa\Controller;
use Think\Controller;
class MemberController extends DomainController {
    /*修改密码*/
    public function password(){
        $old['password']=md5($_POST['oldpwd']);
        $who['parentid']=$_POST['parentid'];
        $data=M('parent')->where($who)->find();
        if($old['password'] != $data['password']){
            $data=array(
                'error'=>'原密码错误'
            );
            $this->apiReturn(0,'读取成功',$data);
        }else{
            $dat['password']=md5($_POST['newpwd']);
            M('parent')->where($who)->save($dat);
            $data=array(
                'right'=>'修改成功',
                'newpwd'=>$_POST['newpwd']
            );
            $this->apiReturn(100,'读取成功',$data);
        }
    }
    /*意见反馈*/
    public function feedback(){
        $datas['parentid']=$_POST['parentid'];
        $datas['parentname']=$_POST['parentname'];
        $datas['content']=$_POST['content'];
        $datas['time']=date('Y-m-d H:i:s');
        M('parent_feedback')->add($datas);
        $data='提交成功';
        $this->apiReturn(100,'提交成功',$data);
    }
    /*测试结果*/
    public function testresult(){
        
        $cwho['parentid']=$_POST['parentid'];
        $parent=M('parent')->where($cwho)->find();

        //student表
        $swhe['parentcard']=$parent['parentcard'];
        $student=M('student')->where($swhe)->find();

        //评测结果
        $wherestudentid['studentid'] = $student['studentid'];
      
        //综合报告
        //处理所有测试结果整合成新数组
        $numbers = M('student_test_zonghe')->where($wherestudentid)->select();
        $data=$numbers;
        $this->apiReturn(100,'读取成功',$data);     
    }
    /*测试结果详情*/
    public function testresultcon(){
        



        
    }
    /*关注院校*/
    public function focuschool(){
        $parentid=$_POST['parentid'];
        $where['parentid'] = $parentid;
        $where['schoolid'] = array('neq','');
        $collectCount=M('parent_collect')->where($where)->count(); //分页,总记录数
        $collectData=M('parent_collect')->join('t_d_university ON t_parent_collect.schoolid = t_d_university.id')->where($where)->field("t_parent_collect.*,t_d_university.id,t_d_university.dxmc,t_d_university.logo,t_d_university.logo,t_d_university.province,t_d_university.rank")->select();       
        $data = $collectData;
        $this->apiReturn(100,'提交成功',$data);
                
    }
    /*活动记录*/
    public function activerecord(){
        $cwho['parentid']=$_POST['parentid'];
        $parent=M('parent')->where($cwho)->find();

        //student表
        $swhe['parentcard']=$parent['parentcard'];
        $student=M('student')->where($swhe)->field("studentid,img")->find();
        $where['StudentID']=$student['studentid'];
        $where['_logic'] = 'and';
        $count=M('da_activation_record')->where($where)->count(); //分页,总记录数
        $queryinfos=M('da_activation_record')->where($where)->order('id DESC')->select();

        //$data['parent'] = $parent;
        //$data['student'] = $student;
        //$data['count'] = $count;
        $data= $queryinfos;
        $this->apiReturn(100,'提交成功',$data);
      
    }
    /*活动记录详情*/
    public function activerecordcon(){
        //父母id
        $parentid=$_POST['parentid'];
         //活动记录id
        $id=$_POST['id'];
        //活动记录的url属性
        $cwho['parentid']=$parentid;
        $parent=M('parent')->where($cwho)->find();

        //学生
        $swhe['parentcard']=$parent['parentcard'];
        $student=M('student')->where($swhe)->field('studentid,studentname,schoolid,classid')->find();
        $school['SchoolID'] = $student['schoolid'];
        $class['ClassId'] =  $student['classid'];
        //学校
        $school = M('school')->where($school)->find();

        //班级
        $class =M('class')->where($class)->find();
        $data['student']=$student;
        $data['school']=$school;
        $data['class']=$class;
        $where['ID']=$id;
        $true=M('da_activation_record')->where($where)->find();
        $json=json_decode($true['jsons'],true);
        $true['newjson']=$json;
        $data['info']=$true;

        //老师评价
        $comment['hid']=$id;
        $commenture=M('comment_huodongjilu')->where($comment)->order('id DESC')->select();
        $data['comment']=$commenture;

        $picture=$true['picture'];
        $picture=substr($picture,0,strlen($picture)-1); 
        $picture=str_replace('"', '', $picture);
        $picture=explode(',', $picture);
        // foreach ($picture as $v) {
        //     $picture.="<img width='100px' height='100px' style='float:left;' src='/Public/Home/images/huodong/{$v}' />";
        // }
        // $picture=substr($picture,5); 
        // $this->assign('picture',$picture);

        $picinfo="";
        foreach ($picture as $v) {
            if(!empty($v)){
                $picinfo.="<img width='100px' height='100px' style='float:left;' src='/Public/Home/images/huodong/{$v}' />";
            }
        }
        $data['picture']=$picture;
        $this->apiReturn(100,'提交成功',$data);
     }
    /*成长档案*/
    public function growup(){
        $dangan['studentid']=$_POST['studentid'];
        $dangantrue=M('da_chengzhangdangan')->where($dangan)->field('id,studentname,time,url')->select();
        $data=$dangantrue;
        $this->apiReturn(100,'提交成功',$data);
        // if($dangantrue){
        //     foreach ($dangantrue as $k => $v) {
        //         $dangantrue[$k]['url']='http://www.yxke12.com/index.php/Parent/Chengzhangdangan/'.$v['url'];
        //     }
        //     $this->assign('dangantrue',$dangantrue);
        // }else{
        //     $this->assign('dangantrue','0');
        // }      
    }
    public function growupcon(){
        //id为成长档案列表所对应的档案id
        $id=$_POST['id'];
        $dangan['studentid']=$_POST['studentid'];
        $dangan['id']=$id;
        $dangantrue=M('da_chengzhangdangan')->where($dangan)->select();
        $data=$dangantrue;
        $this->apiReturn(100,'提交成功',$data);
        // if($dangantrue){
        //     foreach ($dangantrue as $k => $v) {
        //         $dangantrue[$k]['url']='http://www.yxke12.com/index.php/Parent/Chengzhangdangan/'.$v['url'];
        //     }
        //     $this->assign('dangantrue',$dangantrue);
        // }else{
        //     $this->assign('dangantrue','0');
        // }      
    }
    /*模拟志愿*/
    public function simulate(){
        
        $plannedWhere['user_id']=$_POST['studentid'];
        $plannedWhere['_logic'] = 'and';
        // $plannedCount=M('d_user_planned')->where($plannedWhere)->count(); //分页,总记录数
        $plannedData=M('d_user_planned')->join('t_d_batch ON t_d_user_planned.batchid = t_d_batch.id')->where($plannedWhere)->order('t_d_user_planned.id DESC')->field("t_d_user_planned.id,t_d_user_planned.score,t_d_user_planned.create_date,t_d_batch.name")->select();
        //$data['count'] = $plannedCount;
        if($plannedData){
            
            foreach($plannedData as $k=>$v){
                $plannedData[$k]['newdate']=substr($v['create_date'],0,10);
            }
            $data = $plannedData;        
 
        }else{
          $data="";
        }
        $this->apiReturn(100,'提交成功',$data);
        


        
    }
    //模拟志愿详情
    public function simulatecon(){
        $cwho['parentid']=$_POST['parentid'];
        $parent=M('parent')->where($cwho)->find();

        //student表
        $swhe['parentcard']=$parent['parentcard'];
        $student=M('student')->where($swhe)->find();
        $plannedWhere['t_d_user_planned.user_id']=$student['studentid'];
        $plannedWhere['t_d_user_planned.id']=I('post.id');
        $plannedData=M('d_user_planned')->join('t_d_batch ON t_d_user_planned.batchid = t_d_batch.id','LEFT')->where($plannedWhere)->field('t_d_user_planned.id,t_d_batch.name,t_d_batch.year,t_d_batch.batch_score,t_d_batch.remark')->select();
        $plannedUnivWhere['planned_id']=$plannedData[0]['id'];
        $plannedUniv=M('d_user_planned_univ')->where($plannedUnivWhere)->select();
        // $plannedData=M('d_user_planned')->join('t_d_batch ON t_d_user_planned.batchid = t_d_batch.id','LEFT')->join('t_d_user_planned_univ ON t_d_user_planned.id = t_d_user_planned_univ.planned_id','LEFT')->where($plannedWhere)->order('t_d_user_planned.id DESC')->limit($left,$yiye)->field("t_d_user_planned.id,t_d_user_planned.score,t_d_user_planned.create_date,t_d_batch.name,t_d_user_planned_univ.univID,t_d_user_planned_univ.univ_name,t_d_user_planned_univ.univ_seq,t_d_user_planned_univ.major_name,t_d_user_planned_univ.major_seq")->select();
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
        $data['plannedData']=$plannedData;
        $data['plannedUnivs']=$newplan;
        $this->apiReturn(100,'提交成功',$data);    
        

    }
    //关注专业
    public function guanzhuzhuanye(){

        echo "功能待与pc端确认！";
    }
    //交流话题
    public function jiaoliuhuati(){

        echo "功能待与pc端确认！";
    }
    //学校通知
    public function xuexiaotongzhi(){

        echo "功能待与pc端确认！";
    }
    //Ta的课表
    public function tadekebiao(){

        echo "功能待与pc端确认！";
    }
    /*Ta的老师*/
    public function tadelaoshi(){
        $stuid=$_POST['studentid'];
        $wheretid['StudentID']=$stuid;
        $teacherid=M('student')->where($wheretid)->field('TeacherID')->find();
        $tid=$teacherid['teacherid'];
        if($tid){
           $teacher=M('teacher')->where('TeacherID='.$tid)->select();
           $data=$teacher;
           $this->apiReturn(100,'读取成功',$data);
        }else{
            $data="孩子还没有选择老师！";
            $this->apiReturn(0,'读取失败',$data);
        }
        
    }
    //Ta的笔记
    public function tadebiji(){

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
    //Ta的笔记
    public function kaoshichengji(){

        echo "功能待与pc端确认！";
    }
    //教师评价
    public function jiaoshipingjia(){

        $student['studentid'] = $_POST['studentid'];
        $type = $_POST['type'];
        
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
            $this->apiReturn(100,'请求成功',$data);
        }
    }
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
    public function modifygerenxinxi(){
        $phone=$_POST['phone'];
        $parentid=$_POST['parentid'];
        $where['parentid']=$parentid;
        $data['phone']=$phone;
        $res=M('parent')->where($where)->save($data);
        if($res){
           $data="保存成功";
           $this->apiReturn(100,'操作成功',$data);

        }else{
           $data="保存失败";
           $this->apiReturn(0,'操作成功',$data);
        }

    }
    public function modifytouxiang(){

        $pid=$_POST['parentid'];
        $upload = new \Think\Upload();// 实例化上传类
        $upload->autoSub=false;
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg','png','gif', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Public/Parent/images/touxiang/'; // 设置附件上传根目录
        
        $info   =   $upload->upload();
        if($info){
            foreach($info as $file){
                $touxiang= $file['savepath'].$file['savename'];
                $name=$file['savename'];
            }
            $where['parentid']=$pid;
            $dat['touxiang']=$name;
            $res=M('parent')->where($where)->save($dat);
            if($res){
              $data['msg']="修改头像成功！";
              $data['touxiang']=$name;
              $this->apiReturn(100,'请求成功',$data);
            }else{
              $data="修改头像失败！";
              $this->apiReturn(0,'请求失败',$data); 
            }
            
        }else{
            $data="上传头像失败";
            $this->apiReturn(0,'操作失败',$data);
        }   
    }

}


