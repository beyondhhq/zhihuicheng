<?php
namespace App\Controller;
use Think\Controller;
class StudentController extends DomainController {
	/*学生登录*/
    public function login(){
    	$username=I('post.username'); //学生名
        $password=I('post.password'); //学生密码            
        $student=M('student'); //学生表
        $where['LoginName']= $username;
        $where['PassWord']=md5($password);
        $where['_logic'] = 'and';
        $user=$student->where($where)->find();
        if($user){
            $classdata['ClassId']=$user['classid'];
            $classinfo=M('class')->where($classdata)->find();
            $schooldata['SchoolID']=$user['schoolid'];
            $schoolinfo=M('school')->where($schooldata)->find();
            //县
            $cxian['ProvincesID']=$user['xian'];
            $cxians=M('provinces')->where($cxian)->find();
            //市
            $cshi['ProvincesID']=$cxians['pid'];
            $cshis=M('provinces')->where($cshi)->find();
            //省
            $csheng['ProvincesID']=$cshis['pid'];
            $cshengs=M('provinces')->where($csheng)->find();

            $user['sheng']=$cshengs['provincesname'];
            $user['nianji']=$classinfo['grade'];
            $user['banji']=$classinfo['classname'];
            $user['school']=$schoolinfo['schoolname'];
            
            // 是否为课改地区 1是 2否
            $isnew["name"] = $cshengs['provincesname'];
            $new = M("province_new")->where($isnew)->find();
            if ($new) {
                $user['isnew'] = 1;
            }else{
                $user['isnew'] = 0;
            }
            
            $data=$user;

        	$this->apiReturn(100,'登录成功',$data);
        }else{
        	$data = array(
	        'error'=>'用户名或者密码错误'
	    	);
	    	$this->apiReturn(0,'登录失败',$data);
        }
        
    }
    /*填写学生卡号步骤*/
    public function stepcard(){
        $number=I('post.cardnumber'); //卡号
        $pass=I('post.password'); //密码
        $where['card_number']= $number;
        $where['card_pass']=md5($pass);
        $true=M('activation')->where($where)->find();
        if($true){
            $status=$true['status'];
            $time=$true['time'];
            $now=date("Y-m-d H:i:s");
            if($now > $time){
                $data=array(
                    'error'=>'抱歉，此卡号已过期'
                );
                $this->apiReturn(0,'请求成功',$data);
            }elseif($status=='1'){
                $data=array(
                    'error'=>'抱歉，此卡号是已激活卡号'
                );
                $this->apiReturn(0,'请求成功',$data);
            }else{
                $data=array(
                    'cid'=>$true['id']
                );
                $this->apiReturn(100,'请求成功',$data); 
            }
        }else{
            $data=array(
                    'error'=>'卡号不存在或者密码错误'
            );
            $this->apiReturn(0,'请求成功',$data);
        }
    }
    /*填写家长卡号步骤*/
    public function parentcard(){
        $parentcard=$_POST['parentcard'];
        $where['card_number']=$parentcard;
        $true=M('parentcard')->where($where)->find();
        if(!$true){
            $data=array(
                    'error'=>'家长卡号不存在'
            );
            $this->apiReturn(0,'请求成功',$data);
        }else{
            $data=array(
                    'parentcard'=>$parentcard
            );
            $this->apiReturn(100,'请求成功',$data);
        }
    }
    /*填写账号名和密码*/
    public function userpass(){
        $pass=$_POST['password'];
        $user=$_POST['loginname'];
        $where['LoginName']=$user;
        $true=M('student')->where($where)->find();
        if($true){
            $data=array(
                    'error'=>'该账号已存在'
            );
            $this->apiReturn(0,'请求成功',$data); 
        }else{
            $data=array(
                    'loginname'=>$user,
                    'password'=>$pass
            );
            $this->apiReturn(100,'请求成功',$data);
        }
    }
    /*上传头像与填写姓名*/
    public function picname(){
        $studentname=$_POST['studentname'];
        $sex=$_POST['sex'];
        $upload = new \Think\Upload();// 实例化上传类
        $upload->autoSub=false;
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg','png','gif', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Public/Home/images/touxiang/'; // 设置附件上传根目录
        $info   =   $upload->upload();
        if($info){
            foreach($info as $file){
                $touxiang= $file['savepath'].$file['savename'];
            }
            $data=array(
                'studentname'=>$studentname,
                'touxiang'=>$touxiang,
                'sex'=>$sex
            );
            $this->apiReturn(100,'请求成功',$data);
        }else{
            $data=array(
                'studentname'=>$studentname,
                'sex'=>$sex
            );
            $this->apiReturn(100,'请求成功',$data);
        }   
    }
    /*选择地区及学校页*/
    public function address(){
        $where['PID']=0;//省
        $provinces=M('provinces')->where($where)->order('ProvincesID asc')->select();
        $data=array(
            'provinces'=>$provinces
        );
        $this->apiReturn(100,'请求成功',$data);
    }
    /*获取市级数据*/
    public function getshi(){
        $where['PID']=$_POST['shengid'];
        $shi=M('provinces')->where($where)->order('ProvincesID asc')->select();
        $data=array(
            'shi'=>$shi
        );
        $this->apiReturn(100,'请求成功',$data);
    }
    /*获取县级数据*/
    public function getxian(){
        $where['PID']=$_POST['shiid'];
        $xian=M('provinces')->where($where)->order('ProvincesID asc')->select();
        $data=array(
            'xian'=>$xian
        );
        $this->apiReturn(100,'请求成功',$data);
    }
    /*获取学校数据*/
    public function getschool(){
        $where['CityID']=$_POST['xianid'];
        $sch=M('school')->where($where)->order('SchoolID asc')->select();
        $data=array(
            'school'=>$sch
        );
        $this->apiReturn(100,'请求成功',$data);
    }
    /*绑定尾页*/
    public function endpage(){
        $data=array('2015','2016','2017');
        $this->apiReturn(100,'请求成功',$data);
    }
    /*提交绑定*/
    public function addinfos(){
        $year=$_POST['year']; //入学年份
        $class=$_POST['class'];//班级
        $schoolid=$_POST['schoolid'];//学校id
        $cid=$_POST['cid']; //卡id
        $parentcard=$_POST['parentcard']; //家长卡号
        $studentname=$_POST['studentname']; //姓名
        $loginname=$_POST['loginname']; //账号
        $password=$_POST['password']; //密码
        $sex=$_POST['sex']; //性别
        $xianid=$_POST['xianid']; //县级id
        $type=$_POST['type']; //文理类型
        $touxiang=$_POST['touxiang']; //头像

        if(!$touxiang){
            $touxiang='person-touxiang.jpg';
        }

        $nowm=date('m'); //当前月
        $nowy=date('Y'); //当前年
        $cha=$nowy - $year;
        if($cha==0){
            $grade='高一';//高1
        }elseif($cha==1){
            if($nowm >= 9){
               $grade='高二';//高2
            }else{
               $grade='高一';//高1
            }
        }else{//$cha==2
            if($nowm >= 9){
               $grade='高三';//高3
            }else{
               $grade='高二';//高2
            }
        }
        //查询班级 无数据创建班级 strat
        $clas['Grade']=$grade;
        $clas['ClassName']=$class;
        $clas['SchoolID']=$schoolid;
        $clas['_logic'] = 'and';
        $classinfos=M('class')->where($clas)->find();
        if(!$classinfos){
            $cla['Grade']=$grade;
            $cla['ClassName']=$class;
            $cla['SchoolID']=$schoolid;
            M('class')->add($cla);
        }
        //end
        //查询班级id班主任id
        $lass['Grade']=$grade;
        $lass['ClassName']=$class;
        $lass['SchoolID']=$schoolid;
        $lass['_logic'] = 'and';
        $classid=M('class')->where($lass)->find();
        //获取数据
        $data['Cid']=$cid;
        $data['parentcard']=$parentcard;
        $data['StudentName']=$studentname;
        $data['LoginName']=$loginname;
        $data['PassWord']=md5($password);
        $data['Sex']=$sex;
        $data['Img']=$touxiang;
        $data['SchoolID']=$schoolid;
        $data['Xian']=$xianid;
        $data['nianji']=$year;
        $data['ClassID']=$classid['classid'];
        $data['TeacherID']=$classid['teacherid'];
        $data['Type']=$type;
        //信息入库
        $true=M('student')->add($data);
        if($true){
            //更新卡号激活时间
            $where['id']=$cid;
            $change['status']='1';
            $change['actime']=date('Y-m-d H:i:s');
            M('activation')->where($where)->save($change);
            //查询数据
            $who['Cid']=$cid;
            $user=M('student')->where($who)->find();
            $data=array(
                'studentid'=>$user['studentid'],
                'studentname'=>$user['studentname'],
                'classid'=>$user['classid'],
                'teacherid'=>$user['teacherid'],
                'schoolid'=>$user['schoolid'],
                'isvip'=>$user['isvip'],
                'cid'=>$user['cid'],
                'sex'=>$user['sex'],
                'type'=>$user['type'],
                'xian'=>$user['xian'],
                'nianji'=>$user['nianji'],
                'parentcard'=>$user['parentcard'],
                'touxiang'=>$touxiang,
                'loginname'=>$loginname,
                'password'=>$password
            );
            $this->apiReturn(100,'请求成功',$data);
        }else{
            $data='绑定失败，请返回重试';
            $this->apiReturn(0,'绑定失败',$data);
        }
    }

    public function checkgaige(){
        $who['StudentID']=$_POST['studentid'];
        $info=M('student')->where($who)->find();
        $xian['ProvincesID']=$info['xian'];//xian
        $xians=M('provinces')->where($xian)->find();
        $shi['ProvincesID']=$xians['pid'];//shi
        $shis=M('provinces')->where($shi)->find();
        $sheng['ProvincesID']=$shis['pid'];//sheng
        $shengs=M('provinces')->where($sheng)->find();
        $xsheng=$shengs['provincesname'];//所在sheng
        $where['name']=$xsheng;
        $result=M('province_new')->where($where)->find();
        if($result){
            $data='1';
            $this->apiReturn(100,'请求成功',$data);
        }else{
            $data='0';
            $this->apiReturn(100,'请求成功',$data);
        }
    }

}