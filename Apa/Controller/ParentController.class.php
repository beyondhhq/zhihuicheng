<?php
namespace Apa\Controller;
use Think\Controller;
class ParentController extends DomainController {
    /*家长登录*/
    public function login(){
        $username=I('post.loginuser'); //家长名
        $password=I('post.password'); //家长密码    
        $parent=M('parent'); //家长表
        $where['loginuser']= $username;
        $where['password']=md5($password);
        $where['_logic'] = 'and';
        $user=$parent->where($where)->find();
        if($user){
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
            $pcid=$user['parentcard'];
     
            //根据parentcardid取出学生的姓名和学校
            if(!empty($pcid)){
               $pid['parentcard']=$pcid;
               $stuinfo = M('student')->where($pid)->field('StudentName,SchoolID,StudentID,Img,ClassId')->find();
               $schid=$stuinfo['schoolid'];
               $ssid['SchoolID']=$schid;
               $schinfo = M('school')->where($ssid)->field('SchoolName')->find();
               $classid=$stuinfo['classid'];

               $cid['ClassId']=$classid;
               $classinfo = M('class')->where($cid)->field('Grade,ClassName')->find();
               $user['img']=$stuinfo['img'];
               $user['studentgrade']=$classinfo['grade'];
               $user['studentclass']=$classinfo['classname'];
               $user['studentid']=$stuinfo['studentid'];
               $user['child']=$stuinfo['studentname'];
               $user['childschool']=$schinfo['schoolname'];                
            }else{
               $user['img']="";
               $user['studentgrade']="";
               $user['studentclass']="";
               $user['studentid']="";
               $user['child']="";
               $user['childschool']="";

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
    /*判断是否为改革省份*/
    public function checkgaige(){
        $parentid=$_POST['parentid'];
        $who['parentid']=$parentid;
        $info=M('parent')->where($who)->find();
        $xian['ProvincesID']=$info['xian'];//xian
        $xians=M('provinces')->where($xian)->find();
        $shi['ProvincesID']=$xians['pid'];//shi
        $shis=M('provinces')->where($shi)->find();
        $sheng['ProvincesID']=$shis['pid'];//sheng
        $shengs=M('provinces')->where($sheng)->find();
        $xsheng=$shengs['provincesname'];//所在sheng
        $wherec['name']=$xsheng;
        $result=M('province_new')->where($wherec)->find();
        if($result){
            $data='1';
        }else{
            $data='0';
        }
        $this->apiReturn(100,'请求成功',$data);
    }
    //家长端验证家长卡号
    public function stepcard(){
        $number=I('post.cardnumber'); //卡号
        $pass=I('post.password'); //密码
        $where['card_number']= $number;
        $where['card_pass']=$pass;
        $true=M('parentcard')->where($where)->find();
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
                
                $studentcard = str_replace("F","S",$number);
                //判断学生卡是否存在
                $activation=M('activation')->where(array('card_number'=>$studentcard))->find();
                if(empty($activation)){
                   $data='卡号激活失败,未找到相对应的学生卡信息！'; 
                   $this->apiReturn(0,'请求失败',$data);
                }else{
                  if($activation['status'] != 1){
                    $data='卡号激活失败,请先激活学生卡！！'; 
                    $this->apiReturn(0,'请求失败',$data);
                  }else{
                    //判断学生卡是否已经被其他人绑定
                    $is_true = M('parent')->where(array('studentcard'=>$studentcard))->find();
                    if(!empty($is_true)){
                       $data='卡号激活失败,输入的学生卡信息已和其他家长卡号关联！'; 
                       $this->apiReturn(0,'请求失败',$data);
                    }else{
                       $data=$true['id'];
                       $this->apiReturn(100,'请求成功',$data);
                    }
                  }
                }
                
            }
        }else{
            $data='卡号不存在或者密码错误'; //已过期
            $this->apiReturn(0,'请求失败',$data);
        }

    }
    //判断家长账号是否有重复
    public function getparentByLoginname(){
        $loginname=$_POST['loginuser'];
        $where['loginuser'] = $loginname;
        $parent = M('parent')->where($where)->find();
        if($parent){
          $data="此账号已存在请更换其他账号！";
          $this->apiReturn(0,'继续失败',$data);
        }else{
          $data="可以使用";
          $this->apiReturn(100,'操作成功',$data);
        }
    }
    //家长所有信息入库并激活卡
    /*保存激活用户资料信息*/
    public function addinfo(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->autoSub=false;
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Public/Parent/images/touxiang/'; // 设置附件上传根目录
        $info   =   $upload->upload();
        if($info){
            foreach($info as $file){
                $touxiang= $file['savepath'].$file['savename'];
            }
            $image = new \Think\Image(); 
            $image->open("./Public/Parent/images/touxiang/$touxiang");    
            $image->thumb(210, 210,\Think\Image::IMAGE_THUMB_CENTER)->save("./Public/Parent/images/touxiang/$touxiang");
            $data['touxiang']=$touxiang;
        }
        $parentcard=$_POST['cardnumber'];
        $studentcard = str_replace("F","S",$parentcard);
        //获取学生信息
        //$studentinfo=M('student')->where(array('parentcard'=>$parentcard))->find();//学生信息
        $data['cid']=$_POST['cid'];
        $data['parentcard']=$parentcard;
        $data['studentcard']=$studentcard;
        $data['loginuser']=$_POST['loginuser'];
        $data['parentname']=$_POST['parentname'];
        $data['password']=md5($_POST['password']);
        $data['phone']=$_POST['phone'];
        $data['sex']=$_POST['sex'];
        $data['xian']=$_POST['xian'];
        $data['address']=$_POST['address'];

        $true=M('parent')->add($data);
        if($true){
            $where['id']=$_POST['cid'];
            $change['status']='1';
            $change['actime']=date('Y-m-d H:i:s');
            $res=M('parentcard')->where($where)->save($change);
            if($res){
              $data="家长卡激活绑定成功!";
              $this->apiReturn(100,'请求成功',$data);
            }else{
              $data="家长卡绑定成功激活失败!";
              $this->apiReturn(0,'请求失败',$data);
            }
            
        }else{
            $data='家长卡激活绑定失败';
            $this->apiReturn(0,'请求失败',$data);
        }
    }
    //根据手机号查询家长id
    public function findparentidbymobile(){
        $hao=$_POST['shoujihao'];
        $where['phone']=$hao;
        $res=M('parent')->where($where)->field('parentid')->find();
        if($res){
          $data=$res['parentid'];
          $this->apiReturn(100,'请求成功',$data);
        }else{
          $data='该手机号还没有绑定注册信息，无法修改密码!';
          $this->apiReturn(100,'请求成功',$data);
        }
    }
    //修改旧密码
    public function modifyoldmima(){
        $sid=$_POST['parentid'];
        $password=$_POST['password'];
        $rpassword=$_POST['rpassword'];
        if($password==$rpassword){
          $save['password']=md5($password);
          $where['parentid']=$sid;  
          $res=M('parent')->where($where)->save($save);
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