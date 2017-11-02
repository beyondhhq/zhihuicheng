<?php
namespace App\Controller;
use Think\Controller;
class XuekeController extends DomainController {
	/*学科题目*/
    public function xueke(){
        $where['studentid'] = $_POST['studentid'];
        $where['xueke'] = array('neq', ' ');
        $where['_logic'] = 'and';
        $number = M('student_test_result')->where($where)->field('xueke,numbers')->select();
        if(count($number) > '4' or count($number) == '4'){
            $data=array(
                'error'=>"最多测试4次"
            );
            $this->apiReturn(0,'读取失败',$data);
        }else{
            $infos=M("sub_quessqion")->order('Sub_QuesstionID')->select();//题目数据
            $data=array(
                'infos'=>$infos
            );
            $this->apiReturn(100,'读取成功',$data);
        }
    }
	/*学科测试提交*/
    public function result(){
    	//学生id
        $studentid=$_POST['studentid'];
        //学生信息
        $student=M('student')->where('studentid='.$studentid)->find();
        //获取数据
    	$info=json_decode($_POST['data'],true);
        //结果入库处理
        $where['StudentID'] = $studentid;
        $number = M('sub_stuent_score')->where($where)->order('id desc')->limit(1)->select();
        if($number){
            $numbers = $number[0]['sub_number'] + 1;
            foreach($info as $k=>$v){
                $data['StudentID'] = $studentid;
                $data['SubjectID'] = $k;
                $data['Sub_Score'] = $v;
                $data['sub_number'] = $numbers;
                M('sub_stuent_score')->add($data);
            }
        }else{
            foreach($info as $k=>$v){
                $data['StudentID'] = $studentid;
                $data['SubjectID'] = $k;
                $data['Sub_Score'] = $v;
                $data['sub_number'] = '1';
                M('sub_stuent_score')->add($data);
            }
        }
        //合成报告
        $number = M('sub_stuent_score')->where($where)->order('id desc')->limit(1)->select();
        $numbers = $number[0]['sub_number'];
        $where['sub_number'] = $numbers;
        $infos = M('sub_stuent_score')->where($where)->select();
        $data=M("sub_quessqion_relation")->select();
        //合并数据
        foreach($infos as $k=>$v){
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
        
        $arts = $score['地理']+$score['历史']+$score['政治']+$score['语文'];
        $science = $score['物理']+$score['化学']+$score['生物']+$score['数学'];
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
        $user['StudentID'] =$studentid;
        $number=M('sub_stuent_score')->where($user)->order('ID desc')->limit(1)->select();
        $numbers=$number[0]['sub_number'];
        $test['studentid']=$studentid;
        $test['studentname']=$student['studentname'];
        $test['xueke']=$trends;
        $test['numbers']=$numbers;
        $test['time']=date('Y-m-d H:i:s');

        $test['studentname'] = $student['studentname'];
        //班级
        $classc['ClassId'] = $student['classid'];
        $classc =M('class')->where($classc)->find();
        $test['gradename']=$classc['grade'];
        $test['classname']=$classc['classname'];

        M('student_test_result')->add($test);

    	if($info){
            $data = array(
            'num'=>$numbers,
            'studentname'=>$student['studentname'],
            'name'=>$trends,
            'score'=>$valv,
            'text'=>$newResult
            );
            $this->apiReturn(100,'提交成功',$data);
	    }
    }


}