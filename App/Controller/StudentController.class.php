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
            $user['provinceid']=$cshengs['provincesid'];
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
        $where['card_pass']=$pass;
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
    /*ajax检查数据库里是否有此学生*/
    public function checkstudent($student_name,$enrollment_number){
        $student_name=$_POST['studentname'];
        $enrollment_number=$_POST['xuejihao']; 
        $where['StudentName'] =   $student_name;
        $where['EnrollmentNo'] =   $enrollment_number;
        $student = M('Student')->where($where)->find();
        if($student){
          $data="该学生已录入系统";
          $this->apiReturn(0,'继续失败',$data);
        }else{
          $data="可以录入";
          $this->apiReturn(100,'操作成功',$data);
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
    /*检查用户名是否重复*/
    public function getStudentByLoginname(){
        $loginname=$_POST['loginname'];
        $where['LoginName'] =   $loginname;
        $student = M('student')->where($where)->find();
        if($student){
          $data="此账号已存在请更换其他账号！";
          $this->apiReturn(0,'继续失败',$data);
        }else{
          $data="可以使用";
          $this->apiReturn(100,'操作成功',$data);
        }
    }
    public function updateActivationById($id,$params){
        
        return $result;
    }

    //激活信息入库
    public function addinfo(){
        if(IS_POST){
            $upload = new \Think\Upload();// 实例化上传类
            $upload->autoSub=false;
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     './Public/Home/images/touxiang/'; // 设置附件上传根目录
            $info   =   $upload->upload();
            if($info){
                foreach($info as $file){
                    $touxiang= $file['savepath'].$file['savename'];
                }
                $image = new \Think\Image();
                $image->open("./Public/Home/images/touxiang/$touxiang");
                $image->thumb(210, 210,\Think\Image::IMAGE_THUMB_CENTER)->save("./Public/Home/images/touxiang/$touxiang");
                $data['Img']=$touxiang;
            }
            
            //绑卡操作
            //判断当前卡是否已经激活
            $where['id']=$_POST['cid'];
            $where['status']='0';
            $isactivate = M('activation')->where($where)->find();
            if(empty($isactivate)){

                $this->error('当前卡号已被激活！');

            }else{
                $number = $isactivate['card_number'];
                $parent = str_replace('S','F',$number);
                $data['Cid']=$_POST['cid'];
                $data['LoginName']=$_POST['loginname'];
                $data['Xian']=$_POST['xian'];
                $data['Tel']=$_POST['phoneNumber']; 
                $data['PassWord']=md5($_POST['password']);
                $data['parentcard']=$parent;
                $data['Type']=$_POST['type'];
                $data['Sex']=$_POST['sex'];
                $data['ClassID']=$_POST['classid'];
                $data['SchoolID']=$_POST['schoolid'];
                $data['nianji']=$_POST['nianji'];
                $data['Type']=$_POST['type'];
                $data['StudentName']=$_POST['studentname'];
                $data['EnrollmentNo']=$_POST['xjhao'];
                $studentmodel = M('student');
                $true = $studentmodel->where($studentwhere)->add($data);
                if($true){
                
                  $activationwhere['id']=$_POST['cid'];
                  $change['status']='1';
                  $change['actime']=date('Y-m-d H:i:s');
                  $res=M('activation')->where($activationwhere)->save($change);
                  if($res){
                    $data="学生卡激活绑定成功!";
                    $this->apiReturn(100,'请求成功',$data);
                  }else{
                    $data="学生卡绑定成功激活失败!";
                    $this->apiReturn(0,'请求失败',$data);
                  }
                }else{
                  $data='学生卡激活绑定失败';
                  $this->apiReturn(0,'请求失败',$data);
                }

            }
            
                
            
        }

    }
    //根据手机号检查是否注册
    public function findstudentidbymobile(){
        $hao=$_POST['shoujihao'];
        $where['Tel']=$hao;
        $res=M('student')->where($where)->field('StudentID')->find();
        if($res){
          $data=$res['studentid'];
          $this->apiReturn(100,'请求成功',$data);
        }else{
          $data='该手机号还没有绑定注册信息，无法修改密码!';
          $this->apiReturn(100,'请求成功',$data);
        }
    }
    //修改新密码
    public function modifyoldmima(){
        $sid=$_POST['studentid'];
        $password=$_POST['password'];
        $rpassword=$_POST['rpassword'];
        if($password==$rpassword){
          $save['PassWord']=md5($password);
          $where['StudentID']=$sid;  
          $res=M('student')->where($where)->save($save);
          if($res){
            $data='修改密码成功';
            $this->apiReturn(100,'请求成功',$data);
          }else{
            $data='修改密码失败';
            $this->apiReturn(0,'请求失败',$data);
          }
        }else{
          $data="两次输入的密码不一样请重新输入!";
          $this->apiReturn(0,'请求失败',$data);
        }

    }
    //修改新手机号
    public function modifymobile(){
        $sid=$_POST['studentid'];
        $shoujihao=$_POST['shoujihao'];
        $where['StudentID']=$sid; 
        $save['Tel']=$shoujihao;
        $res=M('student')->where($where)->save($save);
        if($res){
            $data='修改密码成功';
            $this->apiReturn(100,'请求成功',$data);
        }else{
            $data='修改密码失败';
            $this->apiReturn(0,'请求失败',$data);
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