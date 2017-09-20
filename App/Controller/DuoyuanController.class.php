<?php
namespace App\Controller;
use Think\Controller;
class DuoyuanController extends DomainController {
	/*多元智能题目*/
    public function duoyuan(){
        $where['studentid'] = $_POST['studentid'];
        $where['duoyuan'] = array('neq',' ');
        $where['_logic'] = 'and';
        $number = M('student_test_result')->where($where)->field('duoyuan,numbers')->select();
        if(count($number) > '3' or count($number) == '3'){
            $data=array(
                'error'=>"最多测试3次"
            );
            $this->apiReturn(0,'读取失败',$data);
        }else{
            $infos=M('zt_question')->order('Z_ID ASC')->select();//题目数据
            $data=array(
                'infos'=>$infos
            );
            $this->apiReturn(100,'读取成功',$data);
        }
    }
	/*多元智能测试提交*/
    public function result(){
        //学生id
        $studentid=$_POST['studentid'];
        //学生信息
        $student=M('student')->where('studentid='.$studentid)->find();
        $info=$_POST['data'];
        //获取数据
        $info=json_decode($_POST['data'],true);
        $where['StudentID']=$studentid;
        $number = M('zt_student_score')->where($where)->order('id desc')->limit(1)->select();
        if($number){
            $numbers = $number[0]['z_number'] + 1;
            foreach($info as $k=>$v){
                $data['StudentID'] = $studentid;
                $data['Z_Type_ID'] = $k;
                $data['Z_Score'] = $v;
                $data['Z_number'] = $numbers;
                M('zt_student_score')->add($data);
            }
        }else{
            foreach($info as $k=>$v){
                $data['StudentID'] = $studentid;
                $data['Z_Type_ID'] = $k;
                $data['Z_Score'] = $v;
                $data['Z_number'] = '1';
                M('zt_student_score')->add($data);
            }
        }
        //报告生成
        $number = M('zt_student_score')->where($where)->order('id desc')->limit(1)->select();
        $number = $number[0]['z_number'];
        $where['Z_number']=$number;
        $where['StudentID']=$studentid;
        $info = M('zt_student_score')->where($where)->select();
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
        //记录数据
        $user['StudentID'] = $studentid;
        $number=M('zt_student_score')->where($user)->order('ID desc')->limit(1)->select();
        $numbers=$number[0]['z_number'];
        $test['studentid']=$studentid;
        $test['studentname']=$student['studentname'];
        $test['mbti']=$code;
        $test['numbers']=$numbers;
        $test['time']=date('Y-m-d H:i:s');
        $test['duoyuan']=$max.','.$maxsk.','.$maxtk;
        $sql=M('student_test_result');
        $result = $sql->add($test);
        
        if($info){
            $data = array(
            'num'=>$numbers,
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
            'text'=>$newResult
            );
            $this->apiReturn(100,'提交成功',$data);
        }
    }


}