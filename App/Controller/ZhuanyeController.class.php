<?php
namespace App\Controller;
use Think\Controller;
class ZhuanyeController extends DomainController {
	/*专业倾向报告*/
    public function zhuanye(){
    	//多元智能测试次数
        $duoyuan['StudentID']=$_POST['studentid'];
        $nduoyuan=M('zt_student_score')->where($duoyuan)->order('id desc')->limit(1)->select();
        if($nduoyuan){
        	$nduoyuans = $nduoyuan[0]['z_number'];
        }else{
        	$nduoyuans = "0";
        }
        //霍兰德测试次数
        $huolande['studentid']=$_POST['studentid'];
        $nhuolande=M('student_huolande_result_one')->where($huolande)->order('id desc')->limit(1)->select();
        if($nhuolande){
        	$nhuolandes = $nhuolande[0]['numbers'];
        }else{
        	$nhuolandes = "0";
        }
        //MBTI测试次数
        $mbti['studentid']=$_POST['studentid'];
        $nmbti=M('student_mbti_result')->where($mbti)->order('id desc')->limit(1)->select();
        if($nmbti){
        	$nmbtis = $nmbti[0]['numbers'];
        }else{
        	$nmbtis = "0";
        }
        //综合报告次数
        $zwhon['studentid']=$_POST['studentid'];
        $zonghenumber = M('student_test_zonghe')->where($zwhon)->order('id desc')->select();
        if($zonghenumber){
            $zonghenumbers = $zonghenumber[0]['numbers'];
        }else{
            $zonghenumbers = "0";
        }
        //综合报告
        $zwho['studentid']=$_POST['studentid'];
		$zonghebaogao = M('student_test_zonghe')->where($zwho)->order('numbers asc')->field('id,numbers,time')->select();
        //职业群
        $number =M('student_test_zonghe')->where($zwho)->order('numbers desc')->field('numbers')->find();
        $numbers = $number['numbers']+1;
        $wheres['studentid'] = $_POST['studentid'];
        $wheres['numbers'] = $numbers;
        $wheres['_logic'] = 'and';
        $info = M('student_test_result')->where($wheres)->select();
        foreach($info as $k=>$v){
            if(!empty($v['huolande'])){
                $huolande = $v['huolande'];
            }
            if(!empty($v['mbti'])){
                $mbti = $v['mbti'];
            }
        }
        $array = $huolande.','.$mbti;
        $zhiye['Code'] = array('in',$array);
        $zhiyeinfos = M('cp_job')->where($zhiye)->select();

        $data=array(
            'max'=>"2",
	        'huolande'=>$nhuolandes,
	        'mbti'=>$nmbtis,
            'zonghe'=>$zonghenumbers,
	        'zonghebaogao'=>$zonghebaogao,
            'zhiyeinfos'=>$zhiyeinfos
	    );
	    $this->apiReturn(100,'读取成功',$data);
    }
    /*生成专业倾向报告*/
    public function result(){
    	
       //  //学生id
       //  $studentid=$_POST['studentid'];
       //  //学生信息
       //  $student=M('student')->where('studentid='.$studentid)->find();
       //  //添加职业群
       //  //$opt = $_POST['checks'];
       //  $number = $_POST['number']+1;
       //  $dataq['studentid'] = $studentid;
       //  $dataq['numbers'] = $number;
       //  $dataq['time'] = date('Y-m-d',time());

       //  $dataq['studentname'] = $student['studentname'];
       //  //班级
       //  $classc['ClassId'] = $student['classid'];
       //  $classc =M('class')->where($classc)->find();
       //  $dataq['gradename']=$classc['grade'];
       //  $dataq['classname']=$classc['classname'];

       //  M('student_test_zonghe')->add($dataq);
       //  //生成报告
       //  $who['numbers'] = $number;
       //  $who['studentid']=$studentid;
       //  $who['_logic'] = 'and';
       //  $info=M('student_test_result')->where($who)->order('id desc')->select();
       
       //  foreach($info as $k=>$v){
       //      if(!empty($v['huolande'])){
       //          $huolande = $v['huolande'];
       //      }
       //      if(!empty($v['mbti'])){
       //          $mbti = $v['mbti'];
       //      }
       //  }
       //  $huolande = explode(',',$huolande);
       //  $huolande=array_merge($huolande); //重建索引
       //  //霍兰德职业
       //  $rwhe['Code']=$huolande[0];
       //  $this->assign('huos',$huolande[0]);
       //  $hldinfo=M('cp_job')->where($rwhe)->select();
       //  if($hldinfo){
       //      foreach($hldinfo as $k=>$v){
       //          $hldinfo[$k]=$v['jobname'];
       //          $hldinfoid[$k]=$v['id'];
       //      }
       //      $hldinfo=implode('、',$hldinfo);
       //  }else{
       //      $one11['H_AnswerCode']=$huolande[0][0];
       //      $inf11=M('hollander_answertype')->where($one11)->find();
       //      $cod1=$inf11['h_profession'];//第一个字母职业
       //      $one22['H_AnswerCode']=$huolande[0][1];
       //      $inf22=M('hollander_answertype')->where($one22)->find();
       //      $cod2=$inf22['h_profession'];//第二个字母职业
       //      $one33['H_AnswerCode']=$huolande[0][2];
       //      $inf33=M('hollander_answertype')->where($one33)->find();
       //      $cod3=$inf33['h_profession'];//第三个字母职业
       //      $hldinfo=$cod1.'、'.$cod2.'、'.$cod3;
       //  }
       //  //mbti职业
       //  $where2['M_RPTYPE']=$mbti;
       //  $info2=M('mbti_result_pro')->where($where2)->find();
       //  $info2=$info2['m_profession'];
       //  //匹配职业
       //  $sql=M('student_test_zonghe');
       //  $zhuanye=$sql->where($who)->find();
       //  $which['id']=array('in',$zhuanye['profession']);
       //  $zhiye = M('cp_job')->where($which)->field('JobName')->select();
       //  foreach($zhiye as $k=>$v){
       //      $zhiye[$k] = $v['jobname'];
       //  }
       //  $zhiyes = implode('、',$zhiye);
       // //匹配专业
       //  $sqls = M('cp_jobrelmajor'); 
       //  $majorid=$sqls->where($which)->field('MajorId')->select();
       //  foreach($majorid as $k=>$v){
       //      $majorid[$k] = $v['majorid'];
       //  }
       //  $majorid = implode(',',$majorid);
       //  $majorids['Id'] = array('in',$majorid);
       //  $ksql = M('cp_major');
       //  $zhuanyeinfo = $ksql->where($majorids)->field('Name')->select();
       //  foreach($zhuanyeinfo as $k=>$v){
       //      $zhuanyeinfo[$k] = $v['name'];
       //  }
       //  $zhuanyeinfo = implode('、',$zhuanyeinfo);

       //  $data=array(
       //      'num'=>$number,
       //      'studentname'=>$student['studentname'],
       //      'number'=>$number,
       //      'huolande'=>$hldinfo,
       //      'mbti'=>$info2,
       //      'zhiye'=>$zhiyes,
       //      'zhuanye'=>$zhuanyeinfo
       //  );
       //  $this->apiReturn(100,'提交成功',$data);
        $studentid=$_POST['studentid'];
        $where['studentid'] = $studentid;
        $sql = M('student_test_zonghe');
        $number =$sql->where($where)->order('numbers desc')->field('numbers')->find();
        $numbers = $number['numbers']+1;
        $wheres['studentid'] = $studentid;
        $wheres['numbers'] = $numbers;
        $info = M('student_test_result')->where($wheres)->select();
        foreach($info as $k=>$v){
            if(!empty($v['huolande'])){
                $huolande = $v['huolande'];
            }
        }
        $date['hollander']=$huolande;

        $numberv = $_POST['number']+1;
        $date['studentid'] = $studentid;
        $swhere['StudentID']=$studentid;
        $studentinfo=M('student')->where($swhere)->field("studentname,classid")->find();
        $date['numbers'] = $numberv;
        $date['time'] = date('Y-m-d',time());
        $date['studentname'] = $studentinfo['studentname'];
        //班级
        $classc['ClassId'] = $studentinfo['classid'];
        $classc =M('class')->where($classc)->find();
        $date['gradename']=$classc['grade'];
        $date['classname']=$classc['classname'];
        $user = M('student_test_zonghe');
        $user->add($date);
        
        if($user){
            $studentid=$_POST['studentid'];
            $student=M('student')->where('studentid='.$studentid)->find();
            //次数
            $number = $_POST['number']+1;
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
               $this->apiReturn(100,'提交成功',$data);
        }else{
            $data='0';
            $this->apiReturn(0,'提交失败',$data);

        }
    }


}