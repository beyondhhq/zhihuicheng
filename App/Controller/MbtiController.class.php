<?php
namespace App\Controller;
use Think\Controller;
class MbtiController extends DomainController {
	/*Mbti题目*/
    public function mbti(){
        $where['studentid'] = $_POST['studentid'];
        $where['mbti'] = array('neq', ' ');
        $where['_logic'] = 'and';
        $info = M('student_test_result')->where($where)->field('mbti,numbers,time')->select();
        if(count($info) == '1'){
            $starttime=$info['0']['time'];
            $starsecond=strtotime($starttime);
            $nowsecond=time();
            $jiange=floor(($nowsecond-$starsecond)/86400);
            //当前月数大于首次填报月数时需要判断是不是间隔大于三
            if($jiange>90){
                  $infos=M('mbti_question')->order('M_ID ASC')->select();//题目数据
                    $data=array(
                      'infos'=>$infos
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
            $infos=M('mbti_question')->order('M_ID ASC')->select();//题目数据
              $data=array(
                'infos'=>$infos
              );
            $this->apiReturn(100,'读取成功',$data);
        }
    }
	/*Mbti测试提交*/
    public function result(){
        //学生id
        $studentid=$_POST['studentid'];
        //学生信息
        $student=M('student')->where('studentid='.$studentid)->find();
        //获取数据
    	$info=json_decode($_POST['data'],true);
        $who['studentid']=$studentid;
        $mbti_result=M('student_mbti_result')->where($who)->order('id desc')->limit(1)->select();
        if($mbti_result){
            $numbers=$mbti_result[0]['numbers'] + 1;
            foreach($info as $k=>$v){
                $mbti['studentid']=$studentid;
                $mbti['studentname']=$student['studentname'];
                $mbti['MID']=$k;
                $mbti['MResult']=$v;
                $mbti['numbers']=$numbers;
                M('student_mbti_result')->add($mbti);
            }
        }else{
            foreach($info as $k=>$v){
                $mbti['studentid']=$studentid;
                $mbti['studentname']=$student['studentname'];
                $mbti['MID']=$k;
                $mbti['MResult']=$v;
                $mbti['numbers']='1';
                M('student_mbti_result')->add($mbti);
            }
        }
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
        $newResult =array_merge($result, $resultt); //合并数组
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
        /*记录操作历史,答案结果入库*/
        $me['studentid']=$studentid;
        $check=M('student_mbti_result')->where($me)->order('id desc')->limit(1)->select();
        $numbers=$check[0]['numbers'];
   
        $test['studentid']=$studentid;
        $test['studentname']=$student['studentname'];
        $test['mbti']=$code;
        $test['numbers']=$numbers;
        $test['time']=date('Y-m-d H:i:s');
        //班级
        $classc['ClassId'] = $student['classid'];
        $classc =M('class')->where($classc)->find();
        $test['gradename']=$classc['grade'];
        $test['classname']=$classc['classname'];

        M('student_test_result')->add($test);
      
        $where1['M_TYPE_NAME_EN']=$code;
        $info1=M('mbti_property')->where($where1)->find();
        
        $where2['M_RPTYPE']=$code;
        $info2=M('mbti_result_pro')->where($where2)->find();

    	if($info){
            $data = array(
            'num'=>$numbers,
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
            'shihezhiye'=>$info2['m_profession']//适合职业
            );
	        $this->apiReturn(100,'提交成功',$data);
	    }
    }


}