<?php
namespace App\Controller;
use Think\Controller;
class ZhiyuantianbaoController extends DomainController{
  /*专家讲座*/
    public function zjjzlist()
    {
        $where['class'] = 2;
        $where['kind'] = 5;
        $info = M('video')->field('ViideoID,VideoName,image,time, VideocSrc')->where($where)->select();
        $this->apiReturn(100,'读取成功',$info);
    }
   /*志愿论证 大学排名*/
    public function daxuepaiming(){
        $sql = M('d_univ_rank');
		//query("select DISTINCT province from t_p_volunteer_encyclopedia where kind='2' order by instr(',北京,天津,河北,山西,内蒙古,辽宁,吉林,黑龙江,上海,江苏,浙江,安徽,福建,江西,山东,河南,湖北,湖南,广东,广西,海南,重庆,四川,贵州,云南,西藏,陕西,甘肃,青海,宁夏,新疆,港澳,',concat(',',province,','))");
		$type=$_POST['type'];
		if($type=="esi"){
          
          $where['type'] = 'ESI全球学科排名';
		  $data = $sql->where($where)->order('rank asc')->select();
 
		}elseif($type=="wushu"){
          
          $wherw['type'] = '武书连大学排名';
		  $data = $sql->where($wherw)->order('rank asc')->select();

		}elseif($type=="xiao"){
          
          $wherx['type'] = '校友会大学排名';
		  $data = $sql->where($wherx)->order('rank asc')->select();

		}
		
		//dump($xiao);
		$this->apiReturn(100,'提交成功',$data);
    	
    }
   /*志愿论证 专业排名*/
    public function zhuanyepaiming(){
        
        $major = $_POST['major'];
		$where['major'] = $major;
		$sql = M('d_major_rank_wsl');
		$data = $sql->where($where)->select();
		//var_dump($sql->_sql());
		$this->apiReturn(100,'提交成功',$data);
    	
    }
    /*志愿论证 往年录取查询返回年份和批次*/
    public function wangnianluquyap(){
    	$info = M('d_enrolled_data')->field('year')->group('year')->order('year desc')->limit('3')->select();
    	$year=array();
    	foreach($info as $k=>$v){
          $year[]=$v['year'];           
    	}
    	$pici=['本科一批','本科二批','专科（高职）'];
        $data['date']=$year;
        $data['pici']=$pici;
        $this->apiReturn(100,'提交成功',$data);

    }
    /*志愿论证 往年录取查询按录取位次*/
    public function wangnianluquweici(){

    	    $provinceid=$_POST['provinceid'];
    	    $ctype=$_POST['type'];
            // $cwho['StudentID']=$studentid;
			// $cinfo=M('student')->where($cwho)->find();
			// //县
			// $cxian['ProvincesID']=$cinfo['xian'];
			// $cxian=M('provinces')->where($cxian)->find();
			// //市
			// $cshi['ProvincesID']=$cxian['pid'];
			// $cshi=M('provinces')->where($cshi)->find();
			// //省
			// $csheng['ProvincesID']=$cshi['pid'];
			// $csheng=M('provinces')->where($csheng)->find();
			// //dump($csheng);
			//$name = $csheng['provincesid'];
			//cookie('provinceid',$name);
			//$provinceid = cookie('provinceid');
			if($ctype == '文科'){
				$type = 1;
			}
			
			if($ctype == '理科'){
				$type = 2;
			}
			$mx = $_POST['maxInp'];
			$ni = $_POST['minInp']; 
			$where['year'] = $_POST['year'];
			$where['name'] = $_POST['pici'];
			$where['province_id'] = $provinceid;
			$sqlb = M('d_batch');
			$infos = $sqlb->where($where)->find();
			
			//dump($infos);
			//var_dump($sqlb->_sql());
			
			/* $sql = M('d_score_rank');
			$wherep['year'] = $_POST['year'];
			$wherep['province_id'] = $provinceid;
			$wherep['major_type_id'] = $type;
			$wherep['low_rank'] = array(array('EGT',$_POST['maxInp']),array('ELT',$_POST['minInp']));
			$info = $sql->where($wherep)->field('score')->order('score desc')->select();
			var_dump($sql->_sql());
			//dump($info);
			$number = count($info)-1;
			$max = $info[0]['score'];
			$min = $info[$number]['score']; */
			//dump($mx);
			//dump($mx);
			
			$sqls = M('d_enrolled_data');
			$batchs['year'] = $_POST['year'];
			$batchs['province_id'] = $provinceid;
			$batchs['batch_id_old'] = $infos['batch_id'];
			$batchs['major_type_id'] = $type;
			$batchs['type'] = 1;//0是大学 1是各专业数据
			$batchs['low_score_rank'] = array(array('egt',$mx),array('elt',$ni));
			$info = $sqls->where($batchs)->field('univ_name,low_score,low_score_rank,major_name,enrolled')->order('low_score_rank asc')->select();
			foreach($info as $k=>$v){
				$univname[$k] = $v['univ_name'];
			}
			if($univname){
			$names = implode(',',$univname);
			$batch['year'] = $_POST['year'];
			$batch['province_id'] = $provinceid;
			$batch['batch_id_old'] = $infos['batch_id'];
			$batch['major_type_id'] = $type;
			$batch['type'] = 0;//0是大学 1是各专业数据
			$batch['univ_name'] = array('in',$names);
			//dump($batch);
			
			$datas = $sqls->where($batch)->field('univ_name,low_score,low_score_rank')->select();
			foreach($info as $k=>$v){
				foreach($datas as $ks=>$vs){
					if($v['univ_name'] == $vs['univ_name']){
						$data[$k]['name1'] = $v['univ_name'];
						$data[$k]['name3'] = $vs['low_score'];
						$data[$k]['name7'] = $v['low_score_rank'];
						$data[$k]['name4'] = $v['major_name'];
						$data[$k]['name6'] = $v['enrolled'];
					}
				}
			}}
			//var_dump($sqls->_sql());
			//dump($data);

		    //dump($data);
		    $this->apiReturn(100,'提交成功',$data);
    	
    }
    /*志愿论证 往年录取查询按录取分数*/
    public function wangnianluqufenshu(){
        $ctype=$_POST['type'];
        $provinceid=$_POST['provinceid'];
		$bathid['name'] = $_POST['pici'];
		$batch = M('d_batch')->where($bathid)->field('batch_id')->find();
		if($ctype == '文科'){
			$type = 1;
		}
		if($ctype == '理科'){
			$type = 2;
		}
		
		$where['year'] = $_POST['year'];
		$where['batch_id_old'] = $batch['batch_id'];
		$where['province_id'] = $provinceid;
		$where['type'] = 0;
		$where['major_type_id'] = $type;
		$sql = M('d_enrolled_data');
		$info = $sql->where($where)->field('univ_name,low_score')->order('low_score desc')->select();
		//var_dump($sql->_sql());
		$wheres['year'] = $_POST['year'];
		$wheres['batch_id_old'] = $batch['batch_id'];
		$wheres['province_id'] = $provinceid;
		$wheres['type'] = 1;
		$wheres['major_type_id'] = $type;
		$wheres['low_score'] = array(array('EGT',$_POST['maxInp']),array('ELT',$_POST['minInp']));
		$datas = M('d_enrolled_data')->where($wheres)->field('univ_name,major_name,low_score,enrolled')->order('low_score desc')->select();
		
		foreach($info as $k=>$v){
			foreach($datas as $kd=>$vd){
				if($vd['univ_name'] == $v['univ_name']){
					$data[$k][$kd]['name1'] = $ctype;
					$data[$k][$kd]['name2'] = $v['low_score'];
					$data[$k][$kd]['name3'] = $v['univ_name'];
					$data[$k][$kd]['name4'] = $vd['major_name'];
					$data[$k][$kd]['name5'] = $vd['low_score'];
					$data[$k][$kd]['name6'] = $vd['enrolled'];
				}
			}
		}
	    $d=count($data,1);
		$newdata=array();
		$m=0;
		foreach($data as $k=>$v){
            foreach($v as $k1=>$v1){
                $newdata[$m]=$v1;
                $m++;
            }
		}
		$this->apiReturn(100,'提交成功',$newdata);
    	
    }
    /*志愿论证 往年录取查询按院校名称*/
    public function wangnianluquyuanxiao(){
    	$ctype=$_POST['type'];
        $provinceid=$_POST['provinceid'];
        $leixing="全部";
        $specials="全部";
        $addr="全部";
        $schoolName = $_POST['schoolname'];	
		//dump($array);
			if($ctype == '文科'){
				$type = 1;
			}
			if($ctype == '理科'){
				$type = 2;
			}
			$year = $_POST['year'];
			$pici = $_POST['pici'];
				
			$where['year'] = $_POST['year'];
			$where['name'] = $_POST['pici'];
			$where['province'] = $provinceid;
			
			$info = M('d_batch')->where($where)->find();
			$batch['year'] = $_POST['year'];
			$batch['batch_id_old'] = $info['batch_id'];
			$batch['province_id'] = $provinceid;
			$batch['major_type_id'] = $type;
			$batch['type'] = 0;//0是大学 1是各专业数据
			$batch['univ_name'] = array('like',"%$schoolname%");
			$sqls = M('d_enrolled_data');
			$datas = $sqls->where($batch)->field('year,univ_name,enrolled,low_score,low_score_rank,average_score,average_score_rank,high_score,high_score_rank')->order('low_score desc')->select();
			//var_dump($sqls->_sql());
			
			$wheres['dxmc'] = array('like',"%$schoolName%");
			if($leixing != '全部'){
				$wheres['yxlx'] = $leixing;
			}
			if($addr != '全部'){
				$wheres['province'] = $addr;
			}
			if($specials == '985'){
				$wheres['is985'] = $specials;
			}
			if($specials == '211'){
				$wheres['is211'] = $specials;
			}
			if($specials == '教育部直属'){
				$wheres['zgbm'] = '教育部';
			}
			$slq = M('d_university');
			$infos = $slq->where($wheres)->field('dxmc')->select();
			foreach($datas as $k=>$v){
				foreach($infos as $ki=>$vi){
					if($v['univ_name'] == $vi['dxmc']){
						$data[$k]['univ_name'] = $v['univ_name'];
						$data[$k]['low_score'] = $v['low_score'];
						$data[$k]['low_score_rank'] = $v['low_score_rank'];
						$data[$k]['average_score'] = $v['average_score'];
						$data[$k]['average_score_rank'] = $v['average_score_rank'];
						$data[$k]['high_score'] = $v['high_score'];
						$data[$k]['high_score_rank'] = $v['high_score_rank'];
						$data[$k]['enrolled'] = $v['enrolled'];
					}
				}
			}
			$newdata=array();
			foreach($data as $k=>$v){
               $newdata[]=$v;
			}
			$this->apiReturn(100,'提交成功',$newdata);
    	
    }
    /*志愿论证 往年录取查询按院校专业*/
    public function wangnianluquzhuanye(){
        $provinceid = $_POST['provinceid'];
        $ctype=$_POST['type'];
		$year = $_POST['year'];
		$pici = $_POST['pici'];
		$name = $_POST['majorname'];
		
		if($ctype == '文科'){
			$type = 1;
		}
		if($ctype == '理科'){
			$type = 2;
		}
		$where['year'] = $year;
		$where['name'] = $pici;
		$where['province'] = $provinceid;

			
		$info = M('d_batch')->where($where)->find();
		
		$batchid = $info['batch_id'];
		$wheres['province_id'] = $provinceid;
		$wheres['year'] = $year;
		$wheres['major_name'] = array('like',"%$name%");
		$wheres['major_type_id'] = $type;
		$wheres['batch_id_old'] = $batchid;
		$wheres['type'] = 1;
		$sql = M('d_enrolled_data');
		$data = $sql->where($wheres)->field('univ_name,major_name,enrolled,low_score,low_score_rank')->select();
		
		$this->apiReturn(100,'提交成功',$data);
    	
    }
   /*志愿论证 分段排名查询*/
    public function fenduanpaiming(){

        $ctype=$_POST['type'];
        $pid=$_POST['provinceid'];
        $y=M('d_score_rank')->field('year')->group('year')->order('year desc')->find();
        $deyear=$y['year'];
        $year = $_POST['year']?$_POST['year']:$deyear;

		if($ctype == '文科' ){
			$type = '1';
		}
		if($ctype == '理科' ){
			$type = '2';
		}
		$wheres['ProvincesID'] = $pid;
		$province = M('d_province')->where($wheres)->find();
		$province = $province['provincesname'];
		
		$where['year'] = $year;
		$where['province_id'] = $pid;
		$where['major_type_id'] = $type;
		$where['remark'] = 0;
		//dump($where);
		$sql = M('d_score_rank');
		$info = $sql->where($where)->order('score desc')->field('province_id,major_type_id,year,score,same_score_number,low_rank')->select();
		$data=array();
		foreach($info as $k=>$v){
			$data[$k]['province_id'] = $province;
			$data[$k]['major_type_id'] = session('type');
			$data[$k]['year'] = $v['year'];
			$data[$k]['score'] = $v['score'];
			$data[$k]['same_score_number'] = $v['same_score_number'];
			$data[$k]['low_rank'] = $v['low_rank'];
		}
		//var_dump($sql->_sql());
		foreach($data as $k=>$v){
			$low_rank[$k] = $v['low_rank'];
		}
		array_multisort($low_rank,SORT_ASC,$data);
		//dump($data);
		
		$result['list']=$data;
		$a=M('d_score_rank')->field('year')->group('year')->order('year desc')->select();
		$b=array();
		foreach($a as $k=>$v){
             $b[$k]=$v['year'];

		}
		$result['date']=$b;
        $this->apiReturn(100,'提交成功',$result);   	
    }
   /*志愿论证 学科排名查询*/
    public function xuekepaiming(){
        $info = M('d_major_rank_jyb')->distinct(true)->field('major')->select();
		$data['list']=$info;
		$major ='安全科学与工程';
		$where['major'] = $major;
		$sql = M('d_major_rank_jyb');
		$data['date']= $sql->where($where)->order('rank asc')->select();
		
		$this->apiReturn(100,'提交成功',$data);
    	
    }
    /*学科详情*/
    public function xuekecon(){
		//dump(I('post.'));
		$major = $_POST['subject']?$_POST['subject']:'安全科学与工程';
		$where['major'] = $major;
		$sql = M('d_major_rank_jyb');
		$data = $sql->where($where)->order('rank asc')->select();
		$this->apiReturn(100,'提交成功',$data);
		//dump($data);
	}
   /*志愿论证 志愿词典*/
    public function zhiyuancidian(){
        $where['located'] = 'categories';

		$info = M('d_user_dic')->where($where)->select();
		$this->apiReturn(100,'提交成功',$info);

    	
    }
   /*志愿论证 模拟志愿填报*/
    public function monizhiyuantianbaochapaiming(){
        $score=$_POST['score'];
        $ctype=$_POST['type'];
        $provinceid=$_POST['provinceid'];

        if(is_numeric($score)){
    
		   if($ctype == '文科' ){
			  $type = '1';
		   }
		   if($ctype == '理科' ){
			  $type = '2';
		   }
		   $wheres['score'] = $score;
		   $wheres['province_id'] = $provinceid;
		   $year=date("Y");$month=date("m");
		   if($month>7){
              $wheres['year'] = $year;
		   }else{
              $wheres['year'] = $year-1;

		   }
		   $wheres['major_type_id'] = $type;

		   $sql = M('d_score_rank');
		   //find ranking
		   $data = $sql->where($wheres)->field('low_rank')->find();
		 
		   $this->apiReturn(100,'提交成功',$data);
        }else{
          $data=array();
          $this->apiReturn(100,'提交分数非数字',$data);

        }
   
    }
    /*志愿论证 模拟志愿填报*/
    public function tianbaopici(){
        $score = $_POST['score'];
		$rank = $_POST['rank'];
		$provinceid = $_POST['provinceid'];
		$ctype=$_POST['type'];
		if($ctype == '文科' ){
			$type = '1';
		}
		if($ctype == '理科' ){
			$type = '2';
		}
		$where['province_id'] = $provinceid;
		$where['year'] = '2017';
		$where['major_type_id'] = $type;
		//dump($where);
		$sql = M('d_batch');
		$info = $sql->where($where)->select();
		foreach($info as $k=>$v){
			if($score >= $v['batch_score']){
				$min_batch_score[] = $v['batch_score'];
			}
			// else{
			// 	$max_batch_score[] = $v['batch_score'];
			// }
		}
		
		if(!empty($min_batch_score)){
			$pos=array_search(max($min_batch_score),$min_batch_score);
		}
		$data['score']=$score;
		$data['low_rank']=$rank;
		$data['info']=$info;
		$data['tuijian']=$min_batch_score[$pos];
		$this->apiReturn(100,'提交成功',$data);
   
    }
    public function zhiyuanbiao(){
    	$studentid=$_POST["studentid"];
        $plannid['t_d_user_planned.user_id'] =$studentid;
		$planned =M('d_user_planned')
		    ->join('t_provinces on t_provinces.ProvincesID = t_d_user_planned.province_id')
		    ->join('t_student on t_student.StudentID = t_d_user_planned.user_id')
		    ->join('t_d_batch on t_d_batch.id = t_d_user_planned.batchid')
            ->where($plannid)->field('t_d_user_planned.*,t_provinces.provincesname,t_student.studentname,t_d_batch.name')->order('t_d_user_planned.create_date DESC')->select();

		if($planned){
                foreach ($planned as $k => $v) {
                	$planned_univ[$k] = M('d_user_planned_univ')->where(array('planned_id'=>$v['id']))->field('univ_name')->order('univ_seq asc')->select();
                	$planned[$k]['univ_num'] = count(array_unique(array_column($planned_univ[$k], 'univ_name')));
                	$planned[$k]['univ_name'] = implode(',',array_unique(array_column($planned_univ[$k], 'univ_name')));
                }
                // dump($planned);
        }else{
        	$planned=array();
        }
        $this->apiReturn(100,'提交成功',$planned);
    }
    //志愿填报信息入库
	public function saveplan(){
	    $ctype=$_POST['type'];
	    $userid=$_POST['studentid'];
	    $provinceid=$_POST['provinceid'];
		if($ctype == '文科' ){
			$type = '1';
		}
		if($ctype == '理科' ){
			$type = '2';
		}
		$id = $_POST['batchid'];
		$score = $_POST['score'];
		$rank = $_POST['rank'];
		//dump(I('post.'));
		$where['id'] = $id;
		$info = M('d_batch')->where($where)->find();
		//dump($info);
		$batchid = $info['batch_id'];
		$user = M('d_user_planned');
		$data['user_id'] = $userid;
		$data['province_id'] = $provinceid;
		$data['major_type_id'] = $type;
		$data['batch_id'] =	$batchid;
		$data['batchid'] =	$id;
		$data['score'] = $score;
		$data['rank'] = $rank;
		$data['create_date'] = date("Y-m-d H:i:s");
		//dump($data);
		$a=$user->add($data);
		if($a){
			$data['planid'] = $a;
			$this->apiReturn(100,'入库成功',$data);
		}else{
			$data = 'error';
			$this->apiReturn(0,'入库失败',$data);
		}
		
		
	}
	public function showzhiyuan(){

		$id = $_POST['batchid'];
		$type = $_POST['planid'];
		$ctype=$_POST['type'];
		$provinceid=$_POST['provinceid'];
		
	    $wheres['id'] = $type;
		
		$batch['id'] = $id;
		$dbatch = M('d_batch')->where($batch)->find();

		// var_dump(M('d_batch')->_sql());
		$batchid = $dbatch['batch_id'];
		$max = $dbatch['univ_num'];
		if($ctype == '文科' ){
			$subject_type = '1';
		}
		if($ctype == '理科' ){
			$subject_type = '2';
		}

		$whereplan['year'] = 2017;
		$whereplan['province_id'] = $provinceid;
		$whereplan['major_type_id'] = $subject_type;
		$whereplan['batch_id'] = $batchid;
		$whereplan['type'] = 0;
		$plannum = M('d_planed_data')->where($whereplan)->field('univ_name,plan')->select();


        $where['province_id'] = $provinceid;
		$where['year'] = '2017';
		$where['major_type_id'] = $subject_type;
		//dump($where);
		$sql = M('d_batch');
		$info2 = $sql->where($where)->select();
		$index=array();
		foreach($info2 as $k=>$v){
           $index[]=$v['id'];
		}
		foreach($index as $k=>$v){
           if($id==$v){
              $inde=$k;

           }

		}
		$dbatch['index']=$inde;		
		$data = array('0'=>A,'1'=>B,'2'=>C,'3'=>D,'4'=>E,'5'=>F,'6'=>G,'7'=>H,'8'=>I,'9'=>J,'10'=>K,'11'=>L,'12'=>M,'13'=>N,'14'=>O,'15'=>P,'16'=>Q,'17'=>R,'18'=>S,'19'=>T,'20'=>U,'21'=>V,'22'=>W,'23'=>X,'24'=>Y,'25'=>Z);
		foreach($data as $k=>$v){
			if($k < $max){
				$array[$k] = $v;
			}
		}
		$sqlplan = M('d_user_planned');
		$result = $sqlplan->where($wheres)->order('id desc')->limit(1)->select();
		// var_dump($sqlplan->_sql());
		$planned_id = $result['0']['id'];
		$scorenew = $result['0']['score'];
		$ranknew = $result['0']['rank'];
		$batchid = $result['0']['batch_id'];
		if($type){
			//update 
			$wherei['planned_id'] = $planned_id;
			$univ = M('d_user_planned_univ')
			->join('t_d_university ON t_d_user_planned_univ.univ_name = t_d_university.dxmc','LEFT')
			->where($wherei)->field('t_d_user_planned_univ.*,t_d_university.syl,t_d_university.yxlx,t_d_university.bxxz,t_d_university.is985,t_d_university.is211,t_d_university.isyan,t_d_university.iszizhu,t_d_university.isart')->select();
			// dump($univ);
	
			if($univ){
				foreach($result as $k=>$v){
					foreach($univ as $ku=>$vu){
						if($v['id'] == $vu['planned_id']){
							$value[$vu['univ_seq']]['univ_seq'] = $vu['univ_seq'];
							$value[$vu['univ_seq']]['planned_id'] = $vu['planned_id'];
							$value[$vu['univ_seq']]['univ_name'] = $vu['univ_name'];
							$value[$vu['univ_seq']]['recruit_code'] = $vu['recruit_code'];

							$value[$vu['univ_seq']]['bxxz'] = $vu['bxxz'];
							$value[$vu['univ_seq']]['yxlx'] = $vu['yxlx'];
							$value[$vu['univ_seq']]['is985'] = $vu['is985'];
							$value[$vu['univ_seq']]['is211'] = $vu['is211'];
							$value[$vu['univ_seq']]['syl'] = $vu['syl'];

							if(empty($feature[$vu['univ_seq']]['bxxz'])){
								$feature[$vu['univ_seq']]['bxxz'] = $vu['bxxz'];
							}
							if(empty($feature[$vu['univ_seq']]['yxlx'])){
								$feature[$vu['univ_seq']]['yxlx'] = $vu['yxlx'];
							}
							if(empty($feature[$vu['univ_seq']]['is985'])){
								$feature[$vu['univ_seq']]['is985'] = $vu['is985'];
							}
							if(empty($feature[$vu['univ_seq']]['is211'])){
								$feature[$vu['univ_seq']]['is211'] = $vu['is211'];
							}
							if(empty($feature[$vu['univ_seq']]['syl'])){
								$feature[$vu['univ_seq']]['syl'] = $vu['syl'] == 1 ? '双一流' : '';
							}
							// if(empty($feature[$vu['univ_seq']]['isyan'])){
							// 	$feature[$vu['univ_seq']]['isyan'] = $vu['isyan'];
							// }
							// if(empty($feature[$vu['univ_seq']]['iszizhu'])){
							// 	$feature[$vu['univ_seq']]['iszizhu'] = $vu['iszizhu'];
							// }
							// if(empty($feature[$vu['univ_seq']]['isart'])){
							// 	$feature[$vu['univ_seq']]['isart'] = $vu['isart'];
							// }
							if(!empty(array_filter($feature[$vu['univ_seq']]))){
								$value[$vu['univ_seq']]['feature'] = implode(',',array_filter($feature[$vu['univ_seq']]));
							}

							$enrolled[$vu['univ_seq']] = M('d_enrolled_data')->where(array('univ_name'=>$vu['univ_name'],'province_id'=>$provinceid,'major_type_id'=>$subject_type,'batch_id'=>$vu['batch_id'],'TYPE'=>0))->field('year,low_score_rank')->order('year desc')->find();

							$value[$vu['univ_seq']]['enrolled'] = $enrolled[$vu['univ_seq']]['year'].'年投档线位次：'.$enrolled[$vu['univ_seq']]['low_score_rank'];

							$value[$vu['univ_seq']]['obey'] = $vu['obey'];
							$value[$vu['univ_seq']]['major_name'][]= '['.$vu['m_recruit_code'].']'.$vu['major_name'];
							$value[$vu['univ_seq']]['major_id'][]= $vu['majorid'];
							$value[$vu['univ_seq']]['major_cost'][]= $costplan['cost'];
							$value[$vu['univ_seq']]['major_plan'][]= $costplan['plan'];
							$wheremajor['Id']=$vu['majorid'];
							$costplan=M('d_planed_data')->where($wheremajor)->field('cost,plan')->find();
                            						
						}
					}
				}




				foreach($array as $k=>$v){
					foreach($value as $kv=>$vv){
						if($v == $vv['univ_seq']){
							$info[$k]['univ_seq'] = $v;
							$info[$k]['planned_id'] = $vv['planned_id'];
							$info[$k]['univ_name'] = $vv['univ_name'];
							$info[$k]['obey'] = $vv['obey'];
							$info[$k]['major_name'] = $vv['major_name'];
							$info[$k]['major_id'] = $vv['major_id'];
							$info[$k]['major_cost'] = $vv['major_cost'];
							$info[$k]['major_plan'] = $vv['major_plan'];
							$info[$k]['feature'] = $vv['feature'];
							$info[$k]['enrolled'] = $vv['enrolled'];
							$info[$k]['recruit_code'] = $vv['recruit_code'];

							$info[$k]['bxxz'] = $vv['bxxz'];
							$info[$k]['yxlx'] = $vv['yxlx'];
							$info[$k]['is985'] = $vv['is985'];
							$info[$k]['is211'] = $vv['is211'];
							$info[$k]['syl'] = $vv['syl'];
							$tiaojian['dxmc']=$vv['univ_name'];
                            $pro=M('d_university')->where($tiaojian)->field('province,city')->select();
                            $info[$k]['province'] = $pro['0']['province'];
                            $info[$k]['city'] = $pro['0']['city'];

                            $whereplan['year'] = 2017;
		                    $whereplan['province_id'] = $provinceid;
		                    $whereplan['major_type_id'] = $subject_type;
		                    $whereplan['batch_id'] = $batchid;
		                    $whereplan['type'] = 0;
		                    $whereplan['univ_name'] =$vv['univ_name'];
		                    $plannum = M('d_planed_data')->where($whereplan)->field('univ_name,plan')->select();
		                    $info[$k]['plan'] = $plannum['0']['plan'];

		                    $where5['year'] = array('in',array('2015','2016','2017'));
			                $where5['province_id'] = $provinceid;
			                $where5['major_type_id'] = $subject_type;
			                $where5['batch_id'] = $batchid;
			                $where5['type'] = 0;
			                $where5['univ_name'] =$vv['univ_name'];
			                $info5 = M('d_enrolled_data')->where($where5)->field('year,univ_name,enrolled,low_score_rank')->select();
			                foreach($info5 as $ki=>$vi){
					          
						         if($vi['year'] == '2015'){
							        $info[$k]['enroll2015'] = $vi['enrolled'];
							        $info[$k]['rank2015'] = $vi['low_score_rank'];
						         }
						         if($vi['year'] == '2016'){
							        $info[$k]['enroll2016'] = $vi['enrolled'];
							        $info[$k]['rank2016'] = $vi['low_score_rank'];
						         }
						         if($vi['year'] == '2017'){
							        $info[$k]['enroll2017'] = $vi['enrolled'];
							        $info[$k]['rank2017'] = $vi['low_score_rank'];
						         }
					          
				            }
							$schools[] = $vv['univ_name'];
							break;
						}
						
					}
				}
				$i=0;
				$infos=array();
				foreach($info as $k=>$v){
                   $infos[$i]=$v;
                   $i++;
				}    
			}else{
				$infos = array();
			}
		}else{
			$infos = array();
        }
        $date['info']=$infos;
        $date['zu']=$info2;
        $date['score']=$scorenew;
        $date['rank']=$ranknew;
        $date['batch']=$dbatch;

        $this->apiReturn(100,'提交成功',$date);


	}
    
    
    public function diquleixing(){
        $sql =M();
		$province=$sql->query("select DISTINCT province from t_d_university");
		$yxlx=$sql->query("select DISTINCT yxlx from t_d_university");
		$data['province']=$province;
		$data['yxlx']=$yxlx;
        $this->apiReturn(100,'提交成功',$data);
    }
    public function selectschool(){
        $score = $_POST['score'];
        $batchid=$_POST['batchid'];
        $batch['id'] = $batchid;
		$dbatch = M('d_batch')->where($batch)->find();
		$batch_id = $dbatch['batch_id'];
		$t=$_POST['type'];
		$low_rank = $_POST['lowrank'];
		$provinceid = $_POST['provinceid']; 
		if($t == '文科' ){
			$types = '1';
		}
		if($t == '理科' ){
			$types = '2';
		}
		//16年最低分
		$wherelows['province_id'] = $provinceid;
		$wherelows['major_type_id'] = $types;
		$wherelows['year'] = 2016;
		$wherelows['type'] = 0;

		$sqll = M('d_enrolled_data');
		$low = $sqll->where($wherelows)->field('low_score')->order('low_score asc')->limit(1)->find();
	
		$lowscore = $low['low_score'];
		if($lowscore > $score){
			$score = $lowscore;
		}
		//17年分数排名
		$sqlrank = M('d_score_rank');
		$wheremaxs['province_id'] = $provinceid;
		$wheremaxs['major_type_id'] = $types;
		$wheremaxs['year'] = 2017;
		$wheremaxs['remark'] = 0;
		$realscore = $sqlrank->where($wheremaxs)->field('score')->order('score desc')->limit(1)->find();
		//dump($realscore);
		if($score > $realscore['score']){
			$score = $realscore['score'];
		}
		//dump($score);
		//推荐的学校
		$maxscore = $score + 10;
		$minscore = $score - 50;
		$wheremax['province_id'] = $provinceid;
		$wheremax['major_type_id'] = $types;
		$wheremax['year'] = 2017;
		$wheremax['score'] = array(array('ELT',$maxscore),array('EGT',$minscore));
		
		$max = $sqlrank->where($wheremax)->field('low_rank')->order('low_rank asc')->select();
		//dump($max);
		//var_dump($sqlrank->_sql());
	
		$mins = reset($max);
		
		$maxs = end($max);
		

		//查找符合的学校
		$wheremin['province_id'] = $provinceid;
		$wheremin['major_type_id'] = $types;
		$wheremin['year'] = 2016;
		$wheremin['batch_id'] = $batch_id;
		$wheremin['type'] = 0;
		$wheremin['low_score_rank'] = array(array('ELT',$maxs['low_rank']),array('EGT',$mins['low_rank']));
		$sqlschool = M('d_enrolled_data');
		$schools = $sqlschool->where($wheremin)->field('univ_name')->select();
		
		//dump($schools);
		//var_dump($sqlschool->_sql());
		if($schools){
		
			foreach($schools as $k=>$v){
				$shoolname[$k] = $v['univ_name'];
			}
			$schoolnames = implode(',',$shoolname);
			//dump($schools);
			$whereuniv['dxmc'] = array('in',$schoolnames);
			//$whereuniv['batch_id'] = $batch_id;
			$sql = M('d_university');
			$infouniv = $sql->where($whereuniv)->field('province,dxmc,city,yxlx,is985,is211,syl,bxxz')->select();
			//var_dump($sql->_sql());
			$whereplan['year'] = 2017;
			$whereplan['province_id'] = $provinceid;
			$whereplan['major_type_id'] = $types;
			$whereplan['batch_id'] = $batch_id;
			$whereplan['type'] = 0;
			$plannum = M('d_planed_data')->where($whereplan)->field('univ_name,plan')->select();
			foreach($infouniv as $k=>$v){
				foreach($plannum as $kp=>$vp){
					if($v['dxmc'] == $vp['univ_name']){
						$infounivs[$k]['province'] = $v['province'];
						$infounivs[$k]['univ_name'] = $v['dxmc'];
						$infounivs[$k]['city'] = $v['city'];
						$infounivs[$k]['yxlx'] = $v['yxlx'];
						$infounivs[$k]['is211'] = $v['is211'];
						$infounivs[$k]['is985'] = $v['is985'];
						$infounivs[$k]['syl'] = $v['syl'];
						$infounivs[$k]['bxxz'] = $v['bxxz'];
						$infounivs[$k]['plan'] = $vp['plan'];
					}
				}
			}
           
			$where5['year'] = array('in',array('2015','2016','2017'));
			$where5['province_id'] = $provinceid;
			$where5['major_type_id'] = $types;
			$where5['batch_id'] = $batch_id;
			$where5['type'] = 0;
			$where5['univ_name'] = array('in',$schoolnames);
			
			$info5 = $sqlschool->where($where5)->field('year,univ_name,enrolled,low_score_rank')->select();
			//var_dump($sqlschool->_sql());
			foreach($infounivs as $k=>$v){
				foreach($info5 as $ki=>$vi){
					if($v['univ_name'] == $vi['univ_name']){
						if($vi['year'] == '2015'){
							$results[$k]['univ_name'] = $v['univ_name'];
							$results[$k]['province'] = $v['province'];
							$results[$k]['city'] = $v['city'];
							$results[$k]['yxlx'] = $v['yxlx'];
							$results[$k]['is211'] = $v['is211'];
							$results[$k]['is985'] = $v['is985'];
							$results[$k]['syl'] = $v['syl'];
							$results[$k]['bxxz'] = $v['bxxz'];
							$results[$k]['plan'] = $v['plan'];
							$results[$k]['enroll2015'] = $vi['enrolled'];
							$results[$k]['rank2015'] = $vi['low_score_rank'];
						}
						if($vi['year'] == '2016'){
							$results[$k]['univ_name'] = $v['univ_name'];
							$results[$k]['province'] = $v['province'];
							$results[$k]['city'] = $v['city'];
							$results[$k]['yxlx'] = $v['yxlx'];
							$results[$k]['is211'] = $v['is211'];
							$results[$k]['is985'] = $v['is985'];
							$results[$k]['syl'] = $v['syl'];
							$results[$k]['bxxz'] = $v['bxxz'];
							$results[$k]['plan'] = $v['plan'];
							// $results[$k]['enroll2015'] = $vi['enroll2015'];
							// $results[$k]['rank2015'] = $vi['rank2015'];
							$results[$k]['enroll2016'] = $vi['enrolled'];
							$results[$k]['rank2016'] = $vi['low_score_rank'];
						}
						if($vi['year'] == '2017'){
							$results[$k]['univ_name'] = $v['univ_name'];
							$results[$k]['province'] = $v['province'];
							$results[$k]['city'] = $v['city'];
							$results[$k]['yxlx'] = $v['yxlx'];
							$results[$k]['is211'] = $v['is211'];
							$results[$k]['is985'] = $v['is985'];
							$results[$k]['syl'] = $v['syl'];
							$results[$k]['bxxz'] = $v['bxxz'];
							$results[$k]['plan'] = $v['plan'];
							// $results[$k]['enroll2015'] = $vi['enroll2015'];
							// $results[$k]['rank2015'] = $vi['rank2015'];
							// $results[$k]['enroll2016'] = $vi['enroll2016'];
							// $results[$k]['rank2016'] = $vi['rank2016'];
							$results[$k]['enroll2017'] = $vi['enrolled'];
							$results[$k]['rank2017'] = $vi['low_score_rank'];
						}
					}
				}
			}
			//var_dump($sqlschool->_sql());
			foreach($results as $k=>$v){
				$year2016[$k] = $v['rank2016'];
			}
			array_multisort($year2016,SORT_ASC,$results);
			$count=count($results);
			$data['count']=$count;
			$data['result']=$results;
			$this->apiReturn(100,'提交成功',$data);

			
		}

    }
    public function getzhuanye(){
        $planid = $_POST['planid'];
		$name = $_POST['univname'];
		$ctype=$_POST['type'];
		$score=$_POST['score'];
		$userid=$_POST['studentid'];
		$provinceid=$_POST['provinceid'];
		$batchid=$_POST['batchid'];
        $batch['id'] = $batchid;

        $wher['batchid'] = $batchid;
		$wher['user_id'] = $userid;
		if($score){
			$wher['score'] = $score;
		}
		if($planid){
          $planid=$_POST['planid'];

		}else{
          $sqlplan = M('d_user_planned');
		  $result = $sqlplan->where($wher)->order('id desc')->limit(1)->select();
		  $planid = $result['0']['id'];

		}


		$dbatch = M('d_batch')->where($batch)->find();
		$batch_id = $dbatch['batch_id'];
		if($ctype == '文科' ){
			$type = '1';
		}
		if($ctype == '理科' ){
			$type = '2';
		}
		// $where['year'] = '2017';
		// $where['batch_id'] = cookie('batchid');
		// $where['province_id'] = cookie('provinceid');
		// $where['major_type_id'] = $type;
		// $where['univ_name'] = $name;
		// $where['type'] = 1;

		$where['t_d_planed_data.year'] = '2017';
		$where['t_d_planed_data.batch_id'] = $batch_id;
		$where['t_d_planed_data.province_id'] = $provinceid;
		$where['t_d_planed_data.major_type_id'] = $type;
		$where['t_d_planed_data.univ_name'] = $name;
		$where['t_d_planed_data.type'] = 1;
		//dump($where);
		$sql = M('d_planed_data');
		// $data = $sql->where($where)->field('Id,univ_name,m_recruit_code,major_name,plan,cost,schoolyear')->order('m_recruit_code asc')->select();

		 $data = $sql->join('t_d_user_planned_univ ON t_d_planed_data.univ_id = t_d_user_planned_univ.univID and t_d_planed_data.major_name = t_d_user_planned_univ.major_name and t_d_user_planned_univ.planned_id = '.$planid,'LEFT')
		 	->where($where)->field('t_d_planed_data.Id,t_d_planed_data.univ_name,t_d_planed_data.m_recruit_code,t_d_planed_data.major_name,t_d_planed_data.plan,t_d_planed_data.cost,t_d_planed_data.schoolyear,t_d_user_planned_univ.id as user_planned_univ_id')->order('m_recruit_code asc')->select();

		// var_dump($sql->_sql());exit;

		$wheres['year'] = '2017';
		$wheres['batch_id'] = $batch_id;
		$wheres['province_id'] = $provinceid;
		$wheres['major_type_id'] = $type;
		$wheres['univ_name'] = $name;
		$wheres['type'] = 0;
		$datas = $sql->where($wheres)->field('univ_name,plan')->select();
		foreach($data as $k=>$v){
			foreach($datas as $ks=>$vs){
				if($v['univ_name'] == $vs['univ_name']){
					$data[$k]['id'] = $v['id'];
					$data[$k]['univ_name'] = $v['univ_name'];
					$data[$k]['m_recruit_code'] = $v['m_recruit_code'];
					$data[$k]['major_name'] = $v['major_name'];
					$data[$k]['plan'] = $v['plan'];
					$data[$k]['cost'] = $v['cost'].'元';
					$data[$k]['schoolyear'] = $v['schoolyear'];
					$data[$k]['schoolplan'] = $vs['plan'];
					$data[$k]['user_planned_univ_id'] = $v['user_planned_univ_id'];
				}
			}
		}
		/* foreach($data as $k=>$v){
			$name[$k] = $v['m_recruit_code'];
		}
		array_multisort($name,SORT_ASC,$data); */
		//var_dump($sql->_sql());
		// dump($data);
		$this->apiReturn(100,'提交成功',$data);

    }
    public function getwangnian(){
    	//dump(I('post.'));
		$year = $_POST['year'];
		$school = $_POST['schoolname'];
		$ctype=$_POST['type'];
        $proid = $_POST['provinceid'];
        $batchid=$_POST['batchid'];
        $rank=$_POST['rank'];
        $batch['id'] = $batchid;
		$dbatch = M('d_batch')->where($batch)->find();
		$batch_id = $dbatch['batch_id'];

		if($ctype == '文科' ){
			$type = '1';
		}
		if($ctype == '理科' ){
			$type = '2';
		}
		$where['province_id'] = $proid;
		$where['type'] = 1;
		$where['major_type_id'] = $type;
		$where['batch_id'] = $batch_id;
		$where['univ_name'] = $school;
		$where['year'] = $year;
		$sql = M('d_enrolled_data');
		//dump($where);
		$data = $sql->where($where)->field('year,univ_name,major_name,enrolled,low_score,low_score_rank,average_score,average_score_rank,high_score,high_score_rank,major_remark')->order('low_score desc')->select();
		//var_dump($sql->_sql());

		//dump($infos);
		if($data){
   
          foreach($data as $k=>$v){

             if($v['low_score_rank'] * 100 > $rank * 105){
             	// echo "易";
				 $data[$k]['difficulty']="易";
			 } else if($v['low_score_rank'] >= $rank * 95 && $v['low_score_rank'] * 100 <= $rank * 105){
				//echo "中";
				 $data[$k]['difficulty']="中";
			 } else if($v['low_score_rank'] * 100 < $rank * 95){
			 	//echo "难";
				 $data[$k]['difficulty']="难";
		     }

		  }

		}
		

		$this->apiReturn(100,'提交成功',$data);
    }
    //专业入库
    public function savezhuanye(){
        //type表示类型分为1和0
        $pagetype = 0;
        //isadjust是是否同意调剂0表示不同意，1表示同意
		$data=$_POST['data'];
		$data=json_decode($data,ture);
		//planid表示志愿表里面的id
		$planid = $_POST['planid'];
		
        // $data[0]["patch"]='A';
        // $data[0]["obey"]='0';
        // $data[0]["major"][]='168828';
        // $data[0]["major"][]='168829';
        // $data[0]["major"][]='168830';

        // $data[1]["patch"]='B';
        // $data[1]["obey"]='1';
        // $data[1]["major"][]='168798';
        // $data[1]["major"][]='168799';
        // $data[1]["major"][]='168800';
      
        // $data[2]["patch"]='C';
        // $data[2]["obey"]='1';
        // $data[2]["major"][]='169166';
        // $data[2]["major"][]='169167';
        // $data[2]["major"][]='169168';

		$plann['id'] = $planid;
		$planed = M('d_user_planned')->where($plann)->field('batch_id,id')->find();		
		// $wheres['Id'] = array('in',$majors);
		// $sql = M('d_planed_data');
		// $info = $sql->where($wheres)->field('Id,batch_id,recruit_code,univ_name,univ_id,m_recruit_code,major_name')->select();
		//var_dump($sql->_sql());

		
		//dump($planid);
		if($pagetype == '0'){
            $adduniv = M('d_user_planned_univ');
            $i=0;
            $cdata=array();
            foreach($data as $k=>$v){
               foreach($v['major'] as $k1=>$v1){
                  $cdata[]=$v1;
               }
            }
            $count=count($cdata); 
			foreach($data as $kj=>$vj){          
               $wheres['Id'] = array('in',$vj['major']);
		       $sql = M('d_planed_data');
		       $b=count($vj['major']);
		       $info = $sql->where($wheres)->field('Id,batch_id,recruit_code,univ_name,univ_id,m_recruit_code,major_name')->select();
		       foreach($vj['major'] as $k=>$v){
			 	 foreach($info as $ki=>$vi){
			 		if($v == $vi['id']){
			 			$addmajor['planned_id'] = $planid;
			 			$addmajor['batch_id'] = $planed['batch_id'];
			 			$addmajor['univID'] = $vi['univ_id'];
			 			$addmajor['recruit_code'] = $vi['recruit_code'];
			 			$addmajor['univ_name'] = $vi['univ_name'];
			 			$addmajor['obey'] = $vj['obey'];
			 			$addmajor['univ_seq'] = $vj['patch'];
			 			$addmajor['majorid'] = $v;
			 			$addmajor['m_recruit_code'] = $vi['m_recruit_code'];
			 			$addmajor['major_name'] = $vi['major_name'];
			 			$addmajor['major_seq'] = $k;		 			
			 			$res=$adduniv->add($addmajor);
			 			if($res){
                           $i++;
			 			}
                     			 			
			 		}
			 	 }
			   }		   

			}
			if($count==$i){
			   $data=1;
               $this->apiReturn(100,'入库成功',$data);
			}else{
               $data=0;
               $this->apiReturn(0,'入库失败',$data);

			}
				
		}
		
    }
    public function delpici(){
        $planid = $_POST['planid'];
		$where['id'] = $planid;
		$sqls = M('d_user_planned');
		$sqls->where($where)->delete();
		//var_dump($sqls->_sql());
		if($sqls){
			$data = 'yes';
			$this->apiReturn(100,'删除成功',$data);
		}else{

			$data='error';
			$this->apiReturn(0,'删除失败',$data);
		}

		

    }
    public function delschool(){
        //dump(I('post.'));
		$report = $_POST['patch'];
		$planned = $_POST['planid'];
		$where['planned_id'] = $planned;
		$where['univ_seq'] = $report;
		//dump($where);
		/* $where['id'] = $planned;
		$planned = M('d_user_planned');
		$planned->where($where)->delete(); */ 
		//var_dump($sql->_sql());
		$sqls = M('d_user_planned_univ');
		$sqls->where($where)->delete();
		//var_dump($sqls->_sql());
		if($sqls){
			$data = 'yes';
			$this->apiReturn(100,'删除成功',$data);
		}else{
			$data='error';
			$this->apiReturn(0,'删除失败',$data);
		}

    }
    public function modifyzhuanye(){
    	$pagetype=1;
    	$planid=$_POST['planid'];
    	$data=$_POST['data'];
    	$data=json_decode($data,true);


        if($pagetype == '1'){
            $i=0;
            $cdata=array();
            foreach($data as $k=>$v){
               foreach($v['major'] as $k1=>$v1){
                  $cdata[]=$v1;
               }
            }
            $count=count($cdata);
            foreach($data as $k=>$v){
                $wheres['Id'] = array('in',$v['major']);
		        $sql = M('d_planed_data');
		        $info = $sql->where($wheres)->field('Id,batch_id,recruit_code,univ_name,univ_id,m_recruit_code,major_name')->select();
                
                $wheretiao['planned_id'] = $planid;
			    $wheretiao['univ_seq'] = $v['patch'];
			    //dump($wheretiao);
			    $sqltiao = M('d_user_planned_univ');
			    $end = $sqltiao->where($wheretiao)->find();
			    if($end){
                    
                    $term['planned_id'] = $end['planned_id'];
			        $term['batch_id'] = $end['batch_id'];
			        $term['univid'] = $end['univid'];
			        $term['recruit_code'] = $end['recruit_code'];
			        $term['univ_name'] = $end['univ_name'];			
			        $sqltiao->where($wheretiao)->delete();
 
                    foreach($v['major'] as $k1=>$v1){
				     foreach($info as $ki=>$vi){
					  if($v1 == $vi['id']){
					 	$infoa['planned_id'] = $term['planned_id'];
					 	$infoa['batch_id'] = $term['batch_id'];
					 	$infoa['univID'] = $term['univid'];
					 	$infoa['recruit_code'] = $term['recruit_code'];
					 	$infoa['univ_name'] = $term['univ_name'];
					 	$infoa['obey'] = $v['obey'];
					 	$infoa['univ_seq'] = $v['patch'];
					 	$infoa['majorid'] = $v1;
					 	$infoa['m_recruit_code'] = $vi['m_recruit_code'];
					 	$infoa['major_name'] = $vi['major_name'];
					 	$infoa['major_seq'] = $k1;
					 	$sqltiao->add($infoa);
					 	$i++;
					  }
				     }
			        }
			    }else{
			    	$wheres['Id'] = array('in',$v['major']);
		            $sql = M('d_planed_data');
		            $info = $sql->where($wheres)->field('Id,batch_id,recruit_code,univ_name,univ_id,m_recruit_code,major_name')->select();
                    foreach($v['major'] as $k1=>$v1){
				     foreach($info as $ki=>$vi){
					  if($v1 == $vi['id']){
					 	$infoa['planned_id'] = $planid;
					 	$infoa['batch_id'] = $vi['batch_id'];
					 	$infoa['univID'] = $vi['univid'];
					 	$infoa['recruit_code'] = $vi['recruit_code'];
					 	$infoa['univ_name'] = $vi['univ_name'];
					 	$infoa['obey'] = $v['obey'];
					 	$infoa['univ_seq'] = $v['patch'];
					 	$infoa['majorid'] = $v1;
					 	$infoa['m_recruit_code'] = $vi['m_recruit_code'];
					 	$infoa['major_name'] = $vi['major_name'];
					 	$infoa['major_seq'] = $k1;
					 	$sqltiao->add($infoa);
					 	$i++;
					  }
				     }
			        }
			    }   

            }
            if($count==$i){
			   $data=1;              
               $this->apiReturn(100,'修改成功',$data);
			}else{
               $data='修改失败';
			   $this->apiReturn(0,'修改失败',$data);

			}
			
			
			
			//dump($infoa);
			
		}

    }

   
}

