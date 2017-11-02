<?php
namespace Ate\Controller;
use Think\Controller;
class TeacherController extends DomainController {
    /*教师登录*/
    public function login(){
        $username=I('post.loginuser'); //教师名
        $password=I('post.password'); //教师密码  
        $parent=M('teacher'); //教师表
        $where['Username']= $username;
        $where['PassWord']=md5($password);
        $where['_logic'] = 'and';
        $user=$parent->where($where)->find();
        
        if($user){   
            
            $data=$user;
            $schoolid=$user['schoolid'];
            $school=M("school");     
            $res=$school->where("schoolid=$schoolid")->find();
            $data["school"]=$res['schoolname'];
            $xian['ProvincesID']=$res['cityid'];//xian
            $xians=M('provinces')->where($xian)->find();
            $shi['ProvincesID']=$xians['pid'];//shi
            $shis=M('provinces')->where($shi)->find();
            $sheng['ProvincesID']=$shis['pid'];//sheng
            $shengs=M('provinces')->where($sheng)->find();
            $xsheng=$shengs['provincesname'];//所在sheng
            $wherec['name']=$xsheng;
            $result=M('province_new')->where($wherec)->find();
            if($result){
                $data['isnew']='1';
            }else{
                $data['isnew']='0';
            }
            $this->apiReturn(100,'登录成功',$data);

        }else{
            $data = array(
            'error'=>'用户名或者密码错误'
            );
            $this->apiReturn(0,'登录失败',$data);
        }  
    }
    //家长端验证家长卡号
    public function stepcard(){
        $number=I('post.cardnumber'); //卡号
        $pass=I('post.password'); //密码
        $where['card_number']= $number;
        $where['card_pass']=$pass;
        $true=M('teachercard')->where($where)->find();
        if($true){
            $status=$true['status'];
            $time=$true['time'];
            $now=date("Y-m-d H:i:s");
            if($now > $time){
                $data='抱歉，此卡号已过期'; //已过期
                $this->apiReturn(0,'请求失败',$data);
            }elseif($status=='1'){      
                $data='抱歉，此卡号是已激活'; //已激活
                $this->apiReturn(0,'请求失败',$data);
            }else{
                $data=$true['id'];
                $this->apiReturn(100,'请求成功',$data);                
                
            }
        }else{
            $data='卡号不存在或者密码错误'; //已过期
            $this->apiReturn(0,'请求失败',$data);
        }

    }
    //判断家长账号是否有重复
    public function getteacherByLoginname(){
        $loginname=$_POST['loginuser'];
        $where['Username'] = $loginname;
        $teacher = M('teacher')->where($where)->find();
        if($teacher){
          $data="此账号已存在请更换其他账号！";
          $this->apiReturn(0,'继续失败',$data);
        }else{
          $data="可以使用";
          $this->apiReturn(100,'操作成功',$data);
        }
    }
    /*保存激活用户资料信息*/
    public function addinfo(){
        if(IS_POST){
            $upload = new \Think\Upload();// 实例化上传类
            $upload->autoSub=false;
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     './Public/Teacher/images/touxiang/'; // 设置附件上传根目录
            $info   =   $upload->upload();
            if($info){
                foreach($info as $file){
                    $touxiang= $file['savepath'].$file['savename'];
                }
                $image = new \Think\Image();
                $image->open("./Public/Teacher/images/touxiang/$touxiang");
                $image->thumb(210, 210,\Think\Image::IMAGE_THUMB_CENTER)->save("./Public/Teacher/images/touxiang/$touxiang");
                $data['touxiang']=$touxiang;
            }

            //绑卡操作
            //判断当前卡是否已经激活
            //获取教师卡号信息
            $teacherdata['id']=$_POST['cid'];
            $teacherinfo=M('teachercard')->where($teacherdata)->find();//教师卡号信息
            if(!empty($teacherinfo) && $teacherinfo['status'] == 1){
                $data="当前卡号已被激活！";
                $this->apiReturn(0,'继续失败',$data);
            }

            // $school = M('school')->where(array('SchoolName'=>I('post.school')))->find();
            // if(empty($school)){
            //     $this->error('输入的学校信息有误！');
            // }

            //判断当前账号是否已被激活
            $data['TeacherName']    =   $_POST['teachername'];
            $data['SchoolID']       =   $_POST['schoolid'];
            $data['phone']          =   $_POST['phone'];
            // $data['sex']            =   $_POST['sex'];

            $teachermodel = M('teacher');
            $teacher = $teachermodel->where($data)->find();
            if(empty($teacher)){
                $data['Cid']            =   $_POST['cid'];
                $data['Teachernumber']  =   $teacherinfo['card_number'];
                $data['PassWord']       =   md5($_POST['password']);
                $data['Username']       =   $_POST['loginuser'];
                $data['xian']           =   $_POST['xian'];
                $data['sex']            =   $_POST['sex'];
                $data['address']        =   $_POST['address'];
                $data['kemu']           =   $_POST['kemu'];
                if($_POST['jiaose'] == '班主任' && !empty($_POST['classid'])){
                    $data['jiaose']     =   '任课老师';
                }else{
                    $data['jiaose']     =   $_POST['jiaose'];
                }

                $true = $teachermodel->add($data);
                if($true){
                    $where['id']=$_POST['cid'];
                    $change['status']='1';
                    $change['actime']=date('Y-m-d H:i:s');
                    $res=M('teachercard')->where($where)->save($change);
                    if($res){
                      $data="教师卡激活绑定成功!";
                      $this->apiReturn(100,'请求成功',$data);
                    }else{
                      $data="教师卡绑定成功激活失败!";
                      $this->apiReturn(0,'请求失败',$data);
                    }

                    //添加班主任信息
                    if($_POST['jiaose'] == '班主任' && !empty($_POST['classid'])){
                        $classwhere['ClassId']=$_POST['classid'];
                        $classwhere['SchoolID']=$_POST['schoolid'];
                        $teacherid = M('class')->where($classwhere)->field('TeacherID')->find();
                        if(!empty($teacherid) && empty($teacherid['teacherid'])){
                            M('class')->where($classwhere)->save(array('TeacherID'=>$true));
                        }
                    }

                }else{
                   
                   $data="教师卡激活绑定失败！";
                   $this->apiReturn(0,'继续失败',$data);

                }
            }else{
              
              $data="教师信息已被其他教师卡绑定！";
              $this->apiReturn(0,'继续失败',$data);

            }
        }
    }
    //根据手机号查询家长id
    public function findteacheridbymobile(){
        $hao=$_POST['shoujihao'];
        $where['phone']=$hao;
        $res=M('teacher')->where($where)->field('TeacherID')->find();
        if($res){
          $data=$res['teacherid'];
          $this->apiReturn(100,'请求成功',$data);
        }else{
          $data='该手机号还没有绑定注册信息，无法修改密码!';
          $this->apiReturn(100,'请求成功',$data);
        }
    }
    //修改旧密码
    public function modifyoldmima(){
        $sid=$_POST['teacherid'];
        $password=$_POST['password'];
        $rpassword=$_POST['rpassword'];
        if($password==$rpassword){
          $save['PassWord']=md5($password);
          $where['TeacherID']=$sid;  
          $res=M('teacher')->where($where)->save($save);
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
        $sid=$_POST['parentid'];
        $shoujihao=$_POST['shoujihao'];
        $where['parentid']=$sid; 
        $save['phone']=$shoujihao;
        $res=M('parent')->where($where)->save($save);
        if($res){
            $data='修改密码成功';
            $this->apiReturn(100,'请求成功',$data);
        }else{
            $data='修改密码失败';
            $this->apiReturn(0,'请求失败',$data);
        }
    }
}