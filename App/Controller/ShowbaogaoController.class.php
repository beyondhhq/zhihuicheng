<?php
namespace App\Controller;
use Think\Controller;
class ShowbaogaoController extends DomainController {
	/*查看专业倾向报告*/
    public function zhuanye(){
        //学生信息
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
		//霍兰德职业
		$rwhe['Code']=$huolande[0];
		$this->assign('huos',$huolande[0]);
		$hldinfo=M('cp_job')->where($rwhe)->select();
		if($hldinfo){
			foreach($hldinfo as $k=>$v){
				$hldinfo[$k]=$v['jobname'];
				$hldinfoid[$k]=$v['id'];
			}
			$hldinfo=implode('、',$hldinfo);
		}else{
			$one11['H_AnswerCode']=$huolande[0][0];
			$inf11=M('hollander_answertype')->where($one11)->find();
			$cod1=$inf11['h_profession'];//第一个字母职业
			$one22['H_AnswerCode']=$huolande[0][1];
			$inf22=M('hollander_answertype')->where($one22)->find();
			$cod2=$inf22['h_profession'];//第二个字母职业
			$one33['H_AnswerCode']=$huolande[0][2];
			$inf33=M('hollander_answertype')->where($one33)->find();
			$cod3=$inf33['h_profession'];//第三个字母职业
			$hldinfo=$cod1.'、'.$cod2.'、'.$cod3;
		}
		//mbti职业
		$where2['M_RPTYPE']=$mbti;
		$info2=M('mbti_result_pro')->where($where2)->find();
		$info2=$info2['m_profession'];
		//匹配专业
		$sql=M('student_test_zonghe');
		$zhuanye=$sql->where($who)->find();

		$which['id']=array('in',$zhuanye['profession']);
		$zhiye = M('cp_job')->where($which)->field('JobName')->select();
		foreach($zhiye as $k=>$v){
			$zhiye[$k] = $v['jobname'];
		}
		$zhiyes = implode('、',$zhiye);
		$sqls = M('cp_jobrelmajor');
		$majorid=$sqls->where($which)->field('MajorId')->select();
		foreach($majorid as $k=>$v){
			$majorid[$k] = $v['majorid'];
		}
		$majorid = implode(',',$majorid);
		$majorids['Id'] = array('in',$majorid);
		$ksql = M('cp_major');
		$zhuanyeinfo = $ksql->where($majorids)->field('Name')->select();
		foreach($zhuanyeinfo as $k=>$v){
			$zhuanyeinfo[$k] = $v['name'];
		}
		$zhuanyeinfo = implode('、',$zhuanyeinfo);

		/*是否发送老师*/
		if($zhuanye['teacherid']){
			$teacher='1';
		}else{
			$teacher='0';
		}

		$data=array(
            'num'=>$number,
            'studentname'=>$student['studentname'],
            'number'=>$number,
            'huolande'=>$hldinfo,
            'mbti'=>$info2,
            'duoyuan'=>$duoinfo,
            'zhiye'=>$zhiyes,
            'zhuanye'=>$zhuanyeinfo,
            'teacher'=>$teacher
        );
        $this->apiReturn(100,'读取成功',$data);

    }
    /*查看多元智能报告*/
    public function duoyuan(){
    	//学生信息
        $studentid=$_POST['studentid'];
        $student=M('student')->where('studentid='.$studentid)->find();
        //次数
        $number = $_POST['num'];
    	$num['Z_number'] = $number;
    	$num['StudentID'] = $studentid;
    	$info = M('zt_student_score')->where($num)->select();
		$data = M('zt_at_ques')->select();
		//合并数组
		foreach($info as $k=>$v){
			foreach($data as $kd=>$vd){
				if($v['z_type_id'] == $vd['zid']){
					$infos[$k]['ztid'] = $vd['ztid'];
					$infos[$k]['z_score'] = $v['z_score'];
				}
			}
		}
		//计算各职能得分
		$score = array();
        foreach($infos as $val) {
            $ztid = $val['ztid'];
            $z_score = $val['z_score'];
            if (!$score[$ztid]) {
                $score[$ztid] = $z_score;
            } else {
                $score[$ztid] += $z_score;
            }
        }
		$type = M("zt_question_type")->getField("Z_Type_ID,Z_QuestionsType");
		foreach($score as $k=>$v){
			foreach($type as $kt=>$vt){
				if($k == $kt){
					$result[$vt]=$v;
				}
			}
		}
		//分数换算%
		foreach($result as $k=>$v){
			if($k == '自然认知智能'){
				$results[$k] = round(($v/18)*100);
			}else{
			$results[$k] = round(($v/24)*100);
		}}
		arsort($results);
		$newResult=array_keys($results); //返回键名
		$vResult=array_values($results); //返回键值
		$valv=implode(',',$vResult);
		//取出得分最高项
		foreach($results as $k=>$v){
			$resultnew[] = $k;
		}
		$max = $resultnew['0'];
		$maxsk = $resultnew['1'];
		$maxtk = $resultnew['2'];
		$where['Z_QuestionsType']=$max;
		$infovalue=M("zt_question_type")->where($where)->find();
		$wheres['Z_QuestionsType']=$maxsk;
		$infovalues=M("zt_question_type")->where($wheres)->find();
		$wheret['Z_QuestionsType']=$maxtk;
		$infovaluet=M("zt_question_type")->where($wheret)->find();
		/*是否发送老师*/
		$why['duoyuan']=array('neq','');
		$why['studentid']=$studentid;
		$why['numbers']=$number;
		$whys=M('student_test_result')->where($why)->find();
		if($whys['teacherid']){
			$teacher='1';
		}else{
			$teacher='0';
		}
		$data = array(
            'num'=>$number,
            'studentname'=>$student['studentname'],
            'zuhe1'=>$infovalue['z_questionstype'],
            'zuhe2'=>$infovalues['z_questionstype'],
            'zuhe3'=>$infovaluet['z_questionstype'],
            'en1'=>$infovalue['z_questionstype_en'],
            'tedian1'=>$infovalue['z_describe'],
            'zhiye1'=>$infovalue['z_profession'],
            'en2'=>$infovalues['z_questionstype_en'],
            'tedian2'=>$infovalues['z_describe'],
            'zhiye2'=>$infovalues['z_profession'],
            'en3'=>$infovaluet['z_questionstype_en'],
            'tedian3'=>$infovaluet['z_describe'],
            'zhiye3'=>$infovaluet['z_profession'],
            'score'=>$valv,
            'text'=>$newResult,
            'teacher'=>$teacher
            );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*查看霍兰德报告*/
    public function huolande(){
    	//学生信息
        $studentid=$_POST['studentid'];
        $student=M('student')->where('studentid='.$studentid)->find();
        //次数
        $number = $_POST['num'];
        $which['studentid']=$studentid;
		$which['numbers']=$number;
		$oneinfo=M('student_huolande_result_one')->where($which)->select();
		$data=M('hollander_question')->select();
		foreach($oneinfo as $k=>$v){
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
			if(!$hresult[$h_an]){ //
				$hresult[$h_an]=$hresul;
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
		$zhi['studentid']=$studentid;
		$zhi['numbers']=$number;
		$zhi['HID']='1';
		$a=M('student_huolande_result_two')->where($zhi)->find();
		$R2=$a['r'];
		$I2=$a['i'];
		$A2=$a['a'];
		$S2=$a['s'];
		$E2=$a['e'];
		$C2=$a['c'];
		/*个人特长各项得分*/
		$ge['studentid']=$studentid;
		$ge['numbers']=$number;
		$ge['HID']='2';
		$b=M('student_huolande_result_two')->where($ge)->find();
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
		$values=array_values($arrall); //返回值
		$keys=array_keys($arrall); //返回键
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
		/*取出结果*/
		$hld=$keys[0].$keys[1].$keys[2];
		$one1['H_AnswerCode']=$hld[0];
		$inf1=M('hollander_answertype')->where($one1)->find();
		$one2['H_AnswerCode']=$hld[1];
		$inf2=M('hollander_answertype')->where($one2)->find();
		$one3['H_AnswerCode']=$hld[2];
		$inf3=M('hollander_answertype')->where($one3)->find();
		$rwhe['Code']=$hld;
		$hldinfo=M('cp_job')->where($rwhe)->select();
		if($hldinfo){
			foreach($hldinfo as $k=>$v){
				$hldinfo[$k]=$v['jobname'];
			}
			$hldinfo=implode(',',$hldinfo);
		}else{
			$one11['H_AnswerCode']=$hld[0];
			$inf11=M('hollander_answertype')->where($one11)->find();
			$cod1=$inf11['h_profession'];//第一个字母职业
			$one22['H_AnswerCode']=$hld[1];
			$inf22=M('hollander_answertype')->where($one22)->find();
			$cod2=$inf22['h_profession'];//第二个字母职业
			$one33['H_AnswerCode']=$hld[2];
			$inf33=M('hollander_answertype')->where($one33)->find();
			$cod3=$inf33['h_profession'];//第三个字母职业
			$hldinfo=$cod1.'、'.$cod2.'、'.$cod3;
		}
		/*是否发送老师*/
		$why['huolande']=array('neq','');
		$why['studentid']=$studentid;
		$why['numbers']=$number;
		$whys=M('student_test_result')->where($why)->find();
		if($whys['teacherid']){
			$teacher='1';
		}else{
			$teacher='0';
		}
		$data = array(
            'num'=>$number,
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
            'en'=>$hld,
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
            'zhiye'=>$hldinfo,
            'teacher'=>$teacher
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*查看MBTI报告*/
    public function mbti(){
    	//学生信息
        $studentid=$_POST['studentid'];
        $student=M('student')->where('studentid='.$studentid)->find();
        //次数
        $number = $_POST['num'];
        $which['studentid']=$studentid;
		$which['numbers']=$number;
		$info=M('student_mbti_result')->where($which)->getField("MID,MResult");
		//取出所有答案 ①one
		$AQ = M("mbti_at_ques");
		$data = $AQ->getField("M_QID,M_Check");
		$class_data = $AQ->getField("M_QID,M_A_Name_CN");
		foreach($info as $k=>$v){
			if($v == $data[$k]){
				$result[$class_data[$k]] +=1;
			}
		}
		//取出所有答案 ②two
		$AQt = M("mbti_at_ques_t");
		$datat = $AQt->getField("M_QID,M_Check");
		$class_datat = $AQt->getField("M_QID,M_A_Name_CN");
		foreach($info as $k=>$v){
			if($v == $datat[$k]){
				$resultt[$class_datat[$k]] +=1;
			}
		}
		$newResult=array_merge($result,$resultt); //合并数组
		$values1=$newResult['外倾']; //值
		$values2=$newResult['内倾'];
	    $values3=$newResult['感觉'];
	    $values4=$newResult['直觉'];
	    $values5=$newResult['思维'];
	    $values6=$newResult['情感'];
	    $values7=$newResult['判断'];
	    $values8=$newResult['知觉'];
	    //MBTI类型匹配
	    $E=$newResult['外倾'];
	    $I=$newResult['内倾'];
	    $E>=$I ? $a='E' : $a='I';
	    $E>=$I ? $a1='外倾' : $a1='内倾';
	    $S=$newResult['感觉'];
	    $N=$newResult['直觉'];
	    $S>=$N ? $b='S' : $b='N';
	    $S>=$N ? $b1='感觉' : $b1='直觉';
	    $T=$newResult['思维'];
	    $F=$newResult['情感'];
	    $T>=$F ? $c='T' : $c='F';
	    $T>=$F ? $c1='思维' : $c1='情感';
	    $J=$newResult['判断'];
	    $P=$newResult['知觉'];
	    $J>=$P ? $d='J' : $d='P';
	    $J>=$P ? $d1='判断' : $d1='知觉';
	    $code=$a.$b.$c.$d; //组合人格类型英文名称
	    $where1['M_TYPE_NAME_EN']=$code;
	    $info1=M('mbti_property')->where($where1)->find();
	    $where2['M_RPTYPE']=$code;
	    $info2=M('mbti_result_pro')->where($where2)->find();
	    /*是否发送老师*/
		$why['mbti']=array('neq','');
		$why['studentid']=$studentid;
		$why['numbers']=$number;
		$whys=M('student_test_result')->where($why)->find();
		if($whys['teacherid']){
			$teacher='1';
		}else{
			$teacher='0';
		}
	    $data = array(
            'num'=>$number,
            'studentname'=>$student['studentname'],
            'values1'=>$values1,//分值8个
            'values2'=>$values2,
            'values3'=>$values3,
            'values4'=>$values4,
            'values5'=>$values5,
            'values6'=>$values6,
            'values7'=>$values7,
            'values8'=>$values8,
            'text1'=>$a1,//类型4个
            'text2'=>$b1,
            'text3'=>$c1,
            'text4'=>$d1,
            'en'=>$info1['m_type_name_en'],//类型英文
            'cn'=>$info1['m_type_name_cn'],//类型中文
            'gexingtezheng'=>$info1['m_abstract_propertz'],//个性特征描述
            'zuzhigongxian'=>$info2['zuzhigx'],//对组织的贡献
            'lingdaoms'=>$info2['lingdaoms'],//领导模式
            'xuexims'=>$info2['xuexims'],//学习模式
            'jiejuewtms'=>$info2['jiejuewtms'],//解决问题模式
            'gongzuohjqxx'=>$info2['gongzuohjqxx'],//工作环境倾向性
            'fazhanjy'=>$info2['fazhanjy'],//发展建议
            'shihezhiye'=>$info2['m_profession'],//适合职业
            'teacher'=>$teacher
        );
	    $this->apiReturn(100,'读取成功',$data);
    }
    /*查看学科报告*/
    public function xueke(){
    	//学生信息
        $studentid=$_POST['studentid'];
        $student=M('student')->where('studentid='.$studentid)->find();
        //次数
        $number = $_POST['num'];
        $num['sub_number'] = $number;
		$num['StudentID'] = $studentid;
		$info = M('sub_stuent_score')->where($num)->select();
		$data=M("sub_quessqion_relation")->select();
		//合并数据
		foreach($info as $k=>$v){
			foreach($data as $kd=>$vd){
				if($v['subjectid'] == $vd['sub_quessqionid']){
					$result[$k]['subject'] = $vd['subject'];
					$result[$k]['sroce'] = $v['sub_score'];
				}
			}
		}
		//计算各学科得分
		foreach($result as $val){
			$scores = $val['sroce'];
			$subject = $val['subject'];
			if(!$score[$subject]){
				$score[$subject] = $scores;
			}else{
				$score[$subject] += $scores;
			}
		}
		$newResult = array_keys($score);
		$vResult = array_values($score);
		$valv=implode(',',$vResult);
		$arts = $score['地理'] + $score['历史'] + $score['政治']+$score['语文'];
		$science = $score['物理'] + $score['化学'] + $score['生物']+$score['数学'];
		$trend = $arts - $science;
		
		$array = array(1 =>array('1' =>'76','2' =>'明显文科型',),2 =>array('1' =>'27','2' =>'偏文科型',),3 =>array('1' =>'-28','2' =>'文理无偏型',),4 =>array('1' =>'-76','2' =>'偏理科型',));
		
		if($trend >= '76'){
			$trends = '明显文科型';
		}else{
			if($trend <= '-76'){
				$trends = '明显理科型';
			}else{
				for($i=2;$i<5;$i++){
					if($array[$i][1] <= $trend and $trend < $array[$i-1][1]){
						$trends = $array[$i][2];
					}
				}
			}
		}
		/*是否发送老师*/
		$why['xueke']=array('neq','');
		$why['studentid']=$studentid;
		$why['numbers']=$number;
		$whys=M('student_test_result')->where($why)->find();
		if($whys['teacherid']){
			$teacher='1';
		}else{
			$teacher='0';
		}
		$data = array(
            'num'=>$number,
            'studentname'=>$student['studentname'],
            'name'=>$trends,
            'score'=>$valv,
            'text'=>$newResult,
            'teacher'=>$teacher
        );
        $this->apiReturn(100,'读取成功',$data);
    }

}