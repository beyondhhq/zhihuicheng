<?php
namespace App\Controller;
use Think\Controller;
class HollanderController extends DomainController {
    /*霍兰德数量*/
    public function hollander(){
        $where['studentid'] = $_POST['studentid'];
        $where['huolande'] = array('neq',' ');
        $info = M('student_test_result')->where($where)->field('huolande,numbers,time')->select();
        if(count($info) == '1'){
            $starttime=$info['0']['time'];
            $starsecond=strtotime($starttime);
            $nowsecond=time();
            $jiange=floor(($nowsecond-$starsecond)/86400);
            //当前月数大于首次填报月数时需要判断是不是间隔大于三
            if($jiange>90){
                  $explain=M('hollander_at_ques')->select();//题目说明
                  $question=M("hollander_question")->select();//题目内容
                  $data=array(
                   'explain'=>$explain,
                   'question'=>$question
                  );
                  $this->apiReturn(100,'读取成功',$data);
            }else{
                 
                  $data = '两次测试间隔需大于3个月！';
                  $this->apiReturn(0,'读取失败',$data);
 
            }
             
        }
        if(count($info) > '2' or count($info) == '2'){
            $data = '最多测试两次';
            $this->apiReturn(0,'读取失败',$data);
        }
        if(count($info)=='0'){
           $explain=M('hollander_at_ques')->select();//题目说明
           $question=M("hollander_question")->select();//题目内容
           $data=array(
              'explain'=>$explain,
              'question'=>$question
            ); 
           $this->apiReturn(100,'读取成功',$data);
        }
      
    }
    /*霍兰德测试提交*/
    public function result(){
        //学生id
        $studentid=$_POST['studentid'];
        //学生信息
        $student=M('student')->where('studentid='.$studentid)->find();
        //获取数据
        $data1=json_decode($_POST['data1'],true);
        $data2=json_decode($_POST['data2'],true);
        $data3=json_decode($_POST['data3'],true);
        /*记录操作历史,答案数据入库*/
        $who['studentid']=$studentid;
        $result_one=M('student_huolande_result_one')->where($who)->order('id desc')->limit(1)->select();
        if($result_one){
            $numbers = $result_one[0]['numbers'] + 1;
            foreach($data1 as $k=>$v){
                $datao['studentid']=$studentid;
                $datao['studentname']=$student['studentname'];
                $datao['HID']=$k;
                $datao['HResult']=$v;
                $datao['numbers']=$numbers;
                M('student_huolande_result_one')->add($datao);
            }
        }else{
            foreach($data1 as $k=>$v){
                $datao['studentid']=$studentid;
                $datao['studentname']=$student['studentname'];
                $datao['HID']=$k;
                $datao['HResult']=$v;
                $datao['numbers']='1';
                M('student_huolande_result_one')->add($datao);
            }
        }
        $arr21=array_pop($data3); //删除最后一个元素
        $arr22=array_pop($data3);
        $arr23=array_pop($data3);
        $arr24=array_pop($data3);
        $arr25=array_pop($data3);
        $arr26=array_pop($data3);
        $arr11=array_pop($data2);
        $arr12=array_pop($data2);
        $arr13=array_pop($data2);
        $arr14=array_pop($data2);
        $arr15=array_pop($data2);
        $arr16=array_pop($data2);
        $arr1=array(     //职业技能
            '1'=>$arr11,'2'=>$arr12,'3'=>$arr13,'4'=>$arr14,'5'=>$arr15,'6'=>$arr16,
        );
        $arr2=array(     //个人特长
            '1'=>$arr21,'2'=>$arr22,'3'=>$arr23,'4'=>$arr24,'5'=>$arr25,'6'=>$arr26,
        );

        $result_two=M('student_huolande_result_two')->where($who)->order('id desc')->limit(1)->select();
        if($result_two){
            $numbers = $result_two[0]['numbers'] + 1;
            $two1['studentid']=$studentid;
            $two1['studentname']=$student['studentname'];
            $two1['HID']='1';
            $two1['R']=array_search("R",$arr1);
            $two1['I']=array_search("I",$arr1);
            $two1['A']=array_search("A",$arr1);
            $two1['S']=array_search("S",$arr1);
            $two1['E']=array_search("E",$arr1);
            $two1['C']=array_search("C",$arr1);
            $two1['numbers']=$numbers;
            M('student_huolande_result_two')->add($two1);
            $two2['studentid']=$studentid;
            $two2['studentname']=$student['studentname'];
            $two2['HID']='2';
            $two2['R']=array_search("R",$arr2);
            $two2['I']=array_search("I",$arr2);
            $two2['A']=array_search("A",$arr2);
            $two2['S']=array_search("S",$arr2);
            $two2['E']=array_search("E",$arr2);
            $two2['C']=array_search("C",$arr2);
            $two2['numbers']=$numbers;
            M('student_huolande_result_two')->add($two2);
        }else{
            $two1['studentid']=$studentid;
            $two1['studentname']=$student['studentname'];
            $two1['HID']='1';
            $two1['R']=array_search("R",$arr1);
            $two1['I']=array_search("I",$arr1);
            $two1['A']=array_search("A",$arr1);
            $two1['S']=array_search("S",$arr1);
            $two1['E']=array_search("E",$arr1);
            $two1['C']=array_search("C",$arr1);
            $two1['numbers']='1';
            M('student_huolande_result_two')->add($two1);
            $two2['studentid']=$studentid;
            $two2['studentname']=$student['studentname'];
            $two2['HID']='2';
            $two2['R']=array_search("R",$arr2);
            $two2['I']=array_search("I",$arr2);
            $two2['A']=array_search("A",$arr2);
            $two2['S']=array_search("S",$arr2);
            $two2['E']=array_search("E",$arr2);
            $two2['C']=array_search("C",$arr2);
            $two2['numbers']='1';
            M('student_huolande_result_two')->add($two2);
        }
        //获取number
        $whichn['studentid']=$studentid;
        $numbers=M('student_huolande_result_one')->where($whichn)->order('id desc')->limit(1)->select();
        foreach($numbers as $k=>$v){
            $numberm=$v['numbers'];
        }
        /*算法*/
        $whoa['studentid']=$studentid;
        $whoa['numbers']=$numberm;
        $whoa['_logic'] = 'and';
        $info1 = M('student_huolande_result_one')->where($whoa)->select();
        $data = M('hollander_question')->select();
        foreach($info1 as $k=>$v){
            foreach($data as $kd=>$vd){
                if($vd['h_qid'] == $v['hid']){
                    $infos[$k]['h_answercode']=$vd['h_answercode'];
                    $infos[$k]['hresult']=$v['hresult'];
                }
                
            }
        }
        $hresult=array();
        foreach($infos as $val){
            $h_an=$val['h_answercode'];
            $hresul=$val['hresult'];
            if(!$hresult[$h_an]){
                $hresult[$h_an] = $hresul;
            }else{
                $hresult[$h_an] += $hresul;
            }
        }
        /*第二三四部分各项得分*/
        $R1=$hresult['R'];
        $I1=$hresult['I'];
        $A1=$hresult['A'];
        $S1=$hresult['S'];
        $E1=$hresult['E'];
        $C1=$hresult['C'];
        /*职业技能各项得分*/
        $wa1['HID']='1';
        $wa1['studentid']=$studentid;
        $wa1['numbers']=$numberm;
        $wa1['_logic'] = 'and';
        $a=M('student_huolande_result_two')->where($wa1)->find();
        $R2=$a['r'];
        $I2=$a['i'];
        $A2=$a['a'];
        $S2=$a['s'];
        $E2=$a['e'];
        $C2=$a['c'];
        /*个人特长各项得分*/
        $wa2['HID']='2';
        $wa2['studentid']=$studentid;
        $wa2['numbers']=$numberm;
        $wa2['_logic'] = 'and';
        $b=M('student_huolande_result_two')->where($wa2)->find();
        $R3=$b['r'];
        $I3=$b['i'];
        $A3=$b['a'];
        $S3=$b['s'];
        $E3=$b['e'];
        $C3=$b['c'];
        /*各项总得分*/
        $R=$R1+$R2+$R3;
        $I=$I1+$I2+$I3;
        $A=$A1+$A2+$A3;
        $S=$S1+$S2+$S3;
        $E=$E1+$E2+$E3;
        $C=$C1+$C2+$C3;
        $arrall=array(
            'R'=>$R,'I'=>$I,'A'=>$A,'S'=>$S,'E'=>$E,'C'=>$C,
        );
        arsort($arrall); //降序
        $values=array_values($arrall);//返回值
        $keys=array_keys($arrall);//返回键
        $score1=round(($values[0]/45)*100); //百分比
        $score2=round(($values[1]/45)*100);
        $score3=round(($values[2]/45)*100);
        $score4=round(($values[3]/45)*100);
        $score5=round(($values[4]/45)*100);
        $score6=round(($values[5]/45)*100);
        $here1['H_AnswerCode']=$keys[0]; //柱状标题
        $text1=M('hollander_answertype')->where($here1)->find();
        $here2['H_AnswerCode']=$keys[1];
        $text2=M('hollander_answertype')->where($here2)->find();
        $here3['H_AnswerCode']=$keys[2];
        $text3=M('hollander_answertype')->where($here3)->find();
        $here4['H_AnswerCode']=$keys[3];
        $text4=M('hollander_answertype')->where($here4)->find();
        $here5['H_AnswerCode']=$keys[4];
        $text5=M('hollander_answertype')->where($here5)->find();
        $here6['H_AnswerCode']=$keys[5];
        $text6=M('hollander_answertype')->where($here6)->find();
        $newResult=array_slice($arrall,0,3,true); //相同职业 取3条
        $newResult=array_keys($newResult); //返回键名
        $H_AIDs=implode('',$newResult);//转换为字符串
        /*记录操作历史,答案结果入库*/
        $me['studentid']=$studentid;
        $check=M('student_huolande_result_one')->where($me)->order('id desc')->limit(1)->select();
        $numbers=$check[0]['numbers'];
     
        $test['studentid']=$studentid;
        $test['studentname']=$student['studentname'];
        $test['huolande']=$H_AIDs;
        $test['numbers']=$numbers;
        session('hollander',$numbers);
        $test['time']=date('Y-m-d H:i:s');

        //$test['studentname'] = session('studentname');
        //班级
        $classc['ClassId'] =$student['classid'];
        $classc =M('class')->where($classc)->find();
        $test['gradename']=$classc['grade'];
        $test['classname']=$classc['classname'];

        M('student_test_result')->add($test);

        $one1['H_AnswerCode']=$H_AIDs[0];
        $inf1=M('hollander_answertype')->where($one1)->find();
        $one2['H_AnswerCode']=$H_AIDs[1];
        $inf2=M('hollander_answertype')->where($one2)->find();
        $one3['H_AnswerCode']=$H_AIDs[2];
        $inf3=M('hollander_answertype')->where($one3)->find();
        
        $rwhe['Code']=$H_AIDs;
        $hldinfo=M('cp_job')->where($rwhe)->select();
        if($hldinfo){
            foreach($hldinfo as $k=>$v){
                $hldinfo[$k]=$v['jobname'];
            }
            $hldinfo=implode(',',$hldinfo);
            /**/
            $data = array(
            'num'=>$numbers,
            'studentname'=>$student['studentname'],
            'score1'=>$score1, //分数
            'score2'=>$score2,
            'score3'=>$score3,
            'score4'=>$score4,
            'score5'=>$score5,
            'score6'=>$score6,
            'text1'=>$text1['h_answertype'], 
            'text2'=>$text2['h_answertype'],
            'text3'=>$text3['h_answertype'],
            'text4'=>$text4['h_answertype'],
            'text5'=>$text5['h_answertype'],
            'text6'=>$text6['h_answertype'],
            'en'=>$H_AIDs, 
            'onetype'=>$inf1['h_answertype'], 
            'onetext'=>$inf1['h_ttitlejianjie'],
            'onetezheng'=>$inf1['h_personality'],
            'oneqingxiang'=>$inf1['h_xqingxiang'],
            'onezhiye'=>$inf1['h_profession'],
            'twotype'=>$inf2['h_answertype'],
            'twotext'=>$inf2['h_ttitlejianjie'],
            'twotezheng'=>$inf2['h_personality'],
            'twoqingxiang'=>$inf2['h_xqingxiang'],
            'twozhiye'=>$inf2['h_profession'],
            'threetype'=>$inf3['h_answertype'],
            'threetext'=>$inf3['h_ttitlejianjie'],
            'threetezheng'=>$inf3['h_personality'],
            'threeqingxiang'=>$inf3['h_xqingxiang'],
            'threezhiye'=>$inf3['h_profession'],
            'zhiye'=>$hldinfo
            );
            $this->apiReturn(100,'提交成功',$data);
            /**/
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
            /**/
            $data = array(
            'num'=>$numbers,
            'studentname'=>$student['studentname'],
            'score1'=>$score1,
            'score2'=>$score2,
            'score3'=>$score3,
            'score4'=>$score4,
            'score5'=>$score5,
            'score6'=>$score6,
            'text1'=>$text1['h_answertype'],
            'text2'=>$text2['h_answertype'],
            'text3'=>$text3['h_answertype'],
            'text4'=>$text4['h_answertype'],
            'text5'=>$text5['h_answertype'],
            'text6'=>$text6['h_answertype'],
            'en'=>$H_AIDs,
            'onetype'=>$inf1['h_answertype'],
            'onetext'=>$inf1['h_ttitlejianjie'],
            'onetezheng'=>$inf1['h_personality'],
            'oneqingxiang'=>$inf1['h_xqingxiang'],
            'onezhiye'=>$inf1['h_profession'],
            'twotype'=>$inf2['h_answertype'],
            'twotext'=>$inf2['h_ttitlejianjie'],
            'twotezheng'=>$inf2['h_personality'],
            'twoqingxiang'=>$inf2['h_xqingxiang'],
            'twozhiye'=>$inf2['h_profession'],
            'threetype'=>$inf3['h_answertype'],
            'threetext'=>$inf3['h_ttitlejianjie'],
            'threetezheng'=>$inf3['h_personality'],
            'threeqingxiang'=>$inf3['h_xqingxiang'],
            'threezhiye'=>$inf3['h_profession'],
            'zhiye'=>$hldinfo
            );
            $this->apiReturn(100,'提交成功',$data);
            /**/
        }

    }
	


}