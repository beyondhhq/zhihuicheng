<?php
namespace App\Controller;
use Think\Controller;
class BaogaoController extends DomainController {
	/*测试结果发送老师*/
    public function result(){
        //学生信息
        $studentid=$_POST['studentid'];
        $student=M('student')->where('studentid='.$studentid)->find();

        $where['studentid']=$studentid;
        $type=$_POST['type'];
        $value=$_POST['value'];
        //更新报告teacherid字段状态
        if($type=='huolande'){
            $where['numbers']=$_POST['num'];
            $where['huolande']=$value;
            $info['teacherid']=$student['teacherid'];
            $true=M('student_test_result')->where($where)->save($info);
        }
        if($type=='mbti'){
            $where['numbers']=$_POST['num'];
            $where['mbti']=$value;
            $info['teacherid']=$student['teacherid'];
            $true=M('student_test_result')->where($where)->save($info);
        }
        if($type=='duoyuan'){
            $where['numbers']=$_POST['num'];
            $where['duoyuan']=$value;
            $info['teacherid']=$student['teacherid'];
            $true=M('student_test_result')->where($where)->save($info);
        }
        if($type=='xueke'){
            $where['numbers']=$_POST['num'];
            $where['xueke']=$value;
            $info['teacherid']=$student['teacherid'];
            $true=M('student_test_result')->where($where)->save($info);
        }
        //更新综合报告teacherid字段状态
        if($type=='zonghe'){
            $where['numbers']=$_POST['num'];
            $where['studentid']=$studentid;
            $info['teacherid']=$student['teacherid'];
            $true=M('student_test_zonghe')->where($where)->save($info);
        }
        
        if($true){
        	$data=array(
            'infos'=>'发送老师成功'
	        );
	        $this->apiReturn(100,'提交成功',$data);
        }
    }
    /*获取报告列表*/
    public function lists(){
        $studentid=$_POST['studentid'];
        $where['studentid']=$_POST['studentid'];
        $type=$_POST['type'];
        if($type=='huolande'){
            $where['huolande']=array('neq','');
        }
        if($type=='mbti'){
            $where['mbti']=array('neq','');
        }
        if($type=='xueke'){
            $where['xueke']=array('neq','');
        }
        $true=M('student_test_result')->where($where)->order('id asc')->select();
        if($type=='huolande'||$type=='mbti'){
           $a=count($true);
           if($a == '1'){
             $starttime=$true['0']['time'];
             $starsecond=strtotime($starttime);
             $nowsecond=time();
             $jiange=floor(($nowsecond-$starsecond)/86400);
             //当前月数大于首次填报月数时需要判断是不是间隔大于三
             if($jiange>90){
                  $enable=1;
                  $msg='可以测试';
             }else{
                  $enable=0;
                  $msg='两次测试间隔需大于3个月！';
             }  
           }
           if($a> '2' or $a == '2'){
             $enable=0;
             $msg = '最多测试两次';
           }
           if($a=='0'){
             $enable=1;
             $msg = '可以测试';
           }
        }
        if($type=='xueke'){
           $a=count($true);
           $who['StudentID']=$studentid;
           $res=M('student')->where($who)->field('nianji')->find();
           $nianji=$res['nianji'];
           $year=date("Y");
           $month=date("m");
           if($year<$nianji){
              return false;
           }else{
              $result=($year-$nianji);
              if($result==0){
                 $newgrade="高一";

              }elseif($result==1){
                if($month>8){
                  $newgrade='高二';

                }else{
                  $newgrade='高一';
 
                }

              }elseif($result==2){
                if($month>8){
                  $newgrade='高三';

                }else{
                  $newgrade='高二';
                }

              }elseif($result==3){
                if($month>6){
                  $newgrade='已毕业';
                }else{
                  $newgrade='高三';
                }
              }
            }
            if($newgrade=='高一'){
               $where['xueke']=array('neq','');
               $info=M('student_test_result')->where($where)->order('id desc')->limit(1)->select();
               $time=$info['0']['time'];
               $yue=substr($time,5,2);
               $ri=substr($time,8,2);
               $nowsecond=time();
               if ($a==0) {
                   $enable=1;
                   $msg = '可以测试';
               }
               if ($a==1) {
                   
                   if($yue==2 or $yue==3 or $yue==4 or $yue==5 or $yue==6 or $yue==7){
                      $enable=0;
                      $msg = '高一学年最后一次测试机会已测试，不能再继续测试';
                   }elseif($yue==9 or $yue==11 or $yue==1){
                        $starttime=$time;
                        $starsecond=strtotime($starttime);
                        $jiange=floor(($nowsecond-$starsecond)/86400);
                        if($jiange>30){
                          $enable=1;
                          $msg='可以测试';
                        }else{
                          $enable=0;
                          $msg='两次测试间隔需大于1个月！';
                        }  

                   }elseif($yue==8 or $yue==10 or $yue==12){
                        $starttime=$time;
                        $starsecond=strtotime($starttime);
                        $jiange=floor(($nowsecond-$starsecond)/86400);
                        $maxday=61-$ri;
                        if($jiange>30){
                          if($jiange>$maxday){
                            
                             $enable=1;
                             $msg='可以测试';

                          }else{
                             $enable=0;
                             $newyue=$yue+2;
                             $msg='你的下次可以测试时间是'.$newyue.'月份';

                          }
                          
                        }else{
                          $enable=0;
                          $msg='两次测试间隔需大于1个月！';
                        }  

                   }
               }
               if ($a==2) {
                   if($yue==2 or $yue==3 or $yue==4 or $yue==5 or $yue==6 or $yue==7){
                      $enable=0;
                      $msg = '高一学年最后一次测试机会已测试，不能再继续测试！';
                   }elseif($yue==8 or $yue==9){
                      $enable=0;
                      $msg='测试时间有问题，暂时无法测试请检查数据！';

                   }elseif($yue==11 or $yue==1){
                        $starttime=$time;
                        $starsecond=strtotime($starttime);
                        $jiange=floor(($nowsecond-$starsecond)/86400);
                        if($jiange>30){
                          $enable=1;
                          $msg='可以测试';
                        }else{
                          $enable=0;
                          $msg='两次测试间隔需大于1个月！';
                        }  

                   }elseif($yue==10 or $yue==12){
                        $starttime=$time;
                        $starsecond=strtotime($starttime);
                        $jiange=floor(($nowsecond-$starsecond)/86400);
                        $maxday=61-$ri;
                        if($jiange>30){
                          if($jiange>$maxday){
                            
                             $enable=1;
                             $msg='可以测试';

                          }else{
                             $enable=0;
                             $newyue=$yue+2;
                             $msg='你的下次可以测试时间是'.$newyue.'月份';

                          }
                          
                        }else{
                          $enable=0;
                          $msg='两次测试间隔需大于1个月！';
                        }  

                   }
                   
               }
               if ($a==3) {
                   if($yue==2 or $yue==3 or $yue==4 or $yue==5 or $yue==6 or $yue==7){
                      $enable=0;
                      $msg = '高一学年最后一次测试机会已测试，不能再继续测试';
                   }elseif($yue==8 or $yue==9 or $yue==10 or $yue==11){
                      $enable=0;
                      $msg='测试时间有问题，暂时无法测试请检查数据！';

                   }elseif($yue==1){
                        $starttime=$time;
                        $starsecond=strtotime($starttime);
                        $jiange=floor(($nowsecond-$starsecond)/86400);
                        if($jiange>30){
                          $enable=1;
                          $msg='可以测试';
                        }else{
                          $enable=0;
                          $msg='两次测试间隔需大于1个月！';
                        }  

                   }elseif($yue==12){
                        $starttime=$time;
                        $starsecond=strtotime($starttime);
                        $nowsecond=time();
                        $jiange=floor(($nowsecond-$starsecond)/86400);
                        $maxday=61-$ri;
                        if($jiange>30){
                          if($jiange>$maxday){
                            
                             $enable=1;
                             $msg='可以测试';

                          }else{
                             $enable=0;
                             $newyue=$yue+2;
                             $msg='你的下次可以测试时间是'.$newyue.'月份';

                          }
                          
                        }else{
                          $enable=0;
                          $msg='两次测试间隔需大于1个月！';
                        }  

                   }
               }
               if ($a> 4 or $a == 4) {
                  $enable=0;
                  $msg = '最多测试四次';
               }

            }else{
              
              $enable=0;
              $msg = '该学生已升为高二学年，不再提供测试';

            }
        }     
        
        $data=array(
        'enable'=>$enable,
        'msg'=>$msg,
        'infos'=>$true
        );
        $this->apiReturn(100,'读取成功',$data);

    }

}