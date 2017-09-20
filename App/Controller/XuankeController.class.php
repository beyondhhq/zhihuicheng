<?php
namespace App\Controller;
use Think\Controller;
class XuankeController extends DomainController {
	/*学科介绍*/
    public function infos(){
        $id['ID'] = $_POST['id'];
        $infos=M('xk_course')->where($id)->find();
        $data=array(
            'infos'=>$infos
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*选科记录*/
    public function xuanke(){
		
		$studentid=$_POST['studentid'];
		//学生信息
        $student=M('student')->where('studentid='.$studentid)->find();

    	$where['StudentID'] = $studentid;
		//查出入学年份and转换成时间戳
		$info = M('student')->where($where)->field('EnrollmentTime')->find();
		$datetime = strtotime($info['enrollmenttime']);
		//算出入学年份和当前年份差数
		$date = date('Y',strtotime($info['enrollmenttime']));
		$now = date('Y');
		$year = ($now - $date);
		//查出选课表中的所有记录
		$user['studentid'] = $studentid;
		$time = M('xuekebianzu')->where($user)->field('time')->order('time desc')->select();
		$number = count($time);
		//转化成时间戳
		foreach($time as $k=>$v){
			$time[$k] = strtotime($v['time']);
		}
		//取出最大时间
		$max = $time['0'];

		//dump(strtotime("2016-01-01 00:00:00"));
		//dump(strtotime("2016-06-01 00:00:00"));
		//dump(strtotime("2017-06-01 00:00:00") - strtotime("2017-05-01 00:00:00"));
		//dump(strtotime("2016-01-01 00:00:00") - strtotime($info['enrollmenttime']));
		
		//相隔时间点
		$timestamps = array('13132800','16675200','18489600','13046400');
		$timestamp = array('13132800','29808000','48297600','51344000');
		//算出具体的每个时间点
		foreach($timestamp as $k=>$v){
			$newtime[$k] = $datetime + $v;
		}
		
		$nowtime = time();
		//年差小于3年 并且总记录条数小于4
		if($year < '3' and $number < '4'){
			for($i=0;$i<4;$i++){
				if($max > $newtime[$i] and $max < $newtime[$i+1] ){
					$maxs = $i;
				}
			}
			$day = $nowtime - $max;
			//dump($day);
			//当前选择时间大于时间点 并两次的时间大于一个月
			if(($nowtime > $newtime[$maxs+1] and $day > $month) or $max == '' ){
				//$data['condition'] = 'yes';
				//---------------
				$s=json_decode($_POST['checks'],true);
		    	$where['studentid']=$studentid;
		    	$info['studentid']=$studentid;

		    	if($s[0]){
		    		$s1['ID']=$s[0];
		    		$infos1=M('xk_course')->where($s1)->find();
		    		$info['xid1']=$s[0];
		    		$info['xlogo1']=$infos1['pict'];
		    		$info['xname1']=$infos1['course_name'];
		    	}
		    	if($s[1]){
		    		$s2['ID']=$s[1];
		    		$infos2=M('xk_course')->where($s2)->find();
		    		$info['xid2']=$s[1];
		    		$info['xlogo2']=$infos2['pict'];
		    		$info['xname2']=$infos2['course_name'];
		    	}
		    	if($s[2]){
		    		$s3['ID']=$s[2];
		    		$infos3=M('xk_course')->where($s3)->find();
		    		$info['xid3']=$s[2];
		    		$info['xlogo3']=$infos3['pict'];
		    		$info['xname3']=$infos3['course_name'];
		    	}

		    	$info['time']=date('Y-m-d H:i:s');

		    	$info['teacherid']=$student['teacherid'];
		    	
		    	$info['studentname'] = $student['studentname'];
				//班级
		        $classc['ClassId'] = $student['classid'];
		        $classc =M('class')->where($classc)->find();
		        $info['gradename']=$classc['grade'];
		        $info['classname']=$classc['classname'];

		    	M('xuekebianzu')->add($info);

		    	$data=array(
		    		'message'=>'yes'
		    	);
				//---------------
			}else{
				$data=array(
		    		'message'=>'yes'//no
		    	);
				//$data['condition'] = 'no';
			}
		}else{
			$data=array(
		    		'message'=>'yes'//no
		    );
			//$data['condition'] = 'no';
			
		}
		
		$this->apiReturn(100,'读取成功',$data);

    }

    /*会员中心-选科记录结果列表*/
    public function lists(){
    	$xueke['studentid']=$_POST['studentid'];
    	$info=M('xuekebianzu')->where($xueke)->order('id DESC')->select();
    	$data=$info;
    	$this->apiReturn(100,'读取成功',$data);
    }


}