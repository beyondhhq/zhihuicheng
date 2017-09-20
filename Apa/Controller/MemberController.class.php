<?php
namespace Apa\Controller;
use Think\Controller;
class MemberController extends DomainController {
    /*修改密码*/
    public function password(){
        $old['password']=md5($_POST['oldpwd']);
        $who['parentid']=$_POST['parentid'];
        $data=M('parent')->where($who)->find();
        if($old['password'] != $data['password']){
            $data=array(
                'error'=>'原密码错误'
            );
            $this->apiReturn(0,'读取成功',$data);
        }else{
            $dat['password']=md5($_POST['newpwd']);
            M('parent')->where($who)->save($dat);
            $data=array(
                'right'=>'修改成功',
                'newpwd'=>$_POST['newpwd']
            );
            $this->apiReturn(100,'读取成功',$data);
        }
    }
    /*意见反馈*/
    public function feedback(){
        $datas['parentid']=$_POST['parentid'];
        $datas['parentname']=$_POST['parentname'];
        $datas['content']=$_POST['content'];
        $datas['time']=date('Y-m-d H:i:s');
        M('parent_feedback')->add($datas);
        $data='提交成功';
        $this->apiReturn(100,'提交成功',$data);
    }
    /*测试结果*/
    public function testresult(){
        
        $cwho['parentid']=$_POST['parentid'];
        $parent=M('parent')->where($cwho)->find();

        //student表
        $swhe['parentcard']=$parent['parentcard'];
        $student=M('student')->where($swhe)->find();

        //评测结果
        $wherestudentid['studentid'] = $student['studentid'];
      
        //综合报告
        //处理所有测试结果整合成新数组
        $numbers = M('student_test_zonghe')->where($wherestudentid)->select();
        $data=$numbers;
        $this->apiReturn(100,'读取成功',$data);     
    }
    /*测试结果详情*/
    public function testresultcon(){
        



        
    }
    /*关注院校*/
    public function focuschool(){
        $parentid=$_POST['parentid'];
        $where['parentid'] = $parentid;
        $where['schoolid'] = array('neq','');
        $collectCount=M('parent_collect')->where($where)->count(); //分页,总记录数
        $collectData=M('parent_collect')->join('t_d_university ON t_parent_collect.schoolid = t_d_university.id')->where($where)->field("t_parent_collect.*,t_d_university.id,t_d_university.dxmc,t_d_university.logo,t_d_university.logo,t_d_university.province,t_d_university.rank")->select();       
        $data = $collectData;
        $this->apiReturn(100,'提交成功',$data);
                
    }
    /*活动记录*/
    public function activerecord(){
        $cwho['parentid']=$_POST['parentid'];
        $parent=M('parent')->where($cwho)->find();

        //student表
        $swhe['parentcard']=$parent['parentcard'];
        $student=M('student')->where($swhe)->field("studentid,img")->find();
        $where['StudentID']=$student['studentid'];
        $where['_logic'] = 'and';
        $count=M('da_activation_record')->where($where)->count(); //分页,总记录数
        $queryinfos=M('da_activation_record')->where($where)->order('id DESC')->select();

        //$data['parent'] = $parent;
        //$data['student'] = $student;
        //$data['count'] = $count;
        $data= $queryinfos;
        $this->apiReturn(100,'提交成功',$data);
      
    }
    /*活动记录详情*/
    public function activerecordcon(){
        //父母id
        $parentid=$_POST['parentid'];
         //活动记录id
        $id=$_POST['id'];
        //活动记录的url属性
        $cwho['parentid']=$parentid;
        $parent=M('parent')->where($cwho)->find();

        //学生
        $swhe['parentcard']=$parent['parentcard'];
        $student=M('student')->where($swhe)->field('studentid,studentname,schoolid,classid')->find();
        $school['SchoolID'] = $student['schoolid'];
        $class['ClassId'] =  $student['classid'];
        //学校
        $school = M('school')->where($school)->find();

        //班级
        $class =M('class')->where($class)->find();
        $data['student']=$student;
        $data['school']=$school;
        $data['class']=$class;
        $where['ID']=$id;
        $true=M('da_activation_record')->where($where)->find();
        $json=json_decode($true['jsons'],true);
        $true['newjson']=$json;
        $data['info']=$true;

        //老师评价
        $comment['hid']=$id;
        $commenture=M('comment_huodongjilu')->where($comment)->order('id DESC')->select();
        $data['comment']=$commenture;

        $picture=$true['picture'];
        $picture=substr($picture,0,strlen($picture)-1); 
        $picture=str_replace('"', '', $picture);
        $picture=explode(',', $picture);
        // foreach ($picture as $v) {
        //     $picture.="<img width='100px' height='100px' style='float:left;' src='/Public/Home/images/huodong/{$v}' />";
        // }
        // $picture=substr($picture,5); 
        // $this->assign('picture',$picture);

        $picinfo="";
        foreach ($picture as $v) {
            if(!empty($v)){
                $picinfo.="<img width='100px' height='100px' style='float:left;' src='/Public/Home/images/huodong/{$v}' />";
            }
        }
        $data['picture']=$picture;
        $this->apiReturn(100,'提交成功',$data);
     }
    /*成长档案*/
    public function growup(){
        $dangan['studentid']=$_POST['studentid'];
        $dangantrue=M('da_chengzhangdangan')->where($dangan)->field('id,studentname,time,url')->select();
        $data=$dangantrue;
        $this->apiReturn(100,'提交成功',$data);
        // if($dangantrue){
        //     foreach ($dangantrue as $k => $v) {
        //         $dangantrue[$k]['url']='http://www.yxke12.com/index.php/Parent/Chengzhangdangan/'.$v['url'];
        //     }
        //     $this->assign('dangantrue',$dangantrue);
        // }else{
        //     $this->assign('dangantrue','0');
        // }      
    }
    public function growupcon(){
        //id为成长档案列表所对应的档案id
        $id=$_POST['id'];
        $dangan['studentid']=$_POST['studentid'];
        $dangan['id']=$id;
        $dangantrue=M('da_chengzhangdangan')->where($dangan)->select();
        $data=$dangantrue;
        $this->apiReturn(100,'提交成功',$data);
        // if($dangantrue){
        //     foreach ($dangantrue as $k => $v) {
        //         $dangantrue[$k]['url']='http://www.yxke12.com/index.php/Parent/Chengzhangdangan/'.$v['url'];
        //     }
        //     $this->assign('dangantrue',$dangantrue);
        // }else{
        //     $this->assign('dangantrue','0');
        // }      
    }
    /*模拟志愿*/
    public function simulate(){
        
        $plannedWhere['user_id']=$_POST['studentid'];
        $plannedWhere['_logic'] = 'and';
        // $plannedCount=M('d_user_planned')->where($plannedWhere)->count(); //分页,总记录数
        $plannedData=M('d_user_planned')->join('t_d_batch ON t_d_user_planned.batchid = t_d_batch.id')->where($plannedWhere)->order('t_d_user_planned.id DESC')->field("t_d_user_planned.id,t_d_user_planned.score,t_d_user_planned.create_date,t_d_batch.name")->select();
        //$data['count'] = $plannedCount;
        if($plannedData){
            
            foreach($plannedData as $k=>$v){
                $plannedData[$k]['newdate']=substr($v['create_date'],0,10);
            }
            $data = $plannedData;        
 
        }else{
          $data="";
        }
        $this->apiReturn(100,'提交成功',$data);
        


        
    }
    //模拟志愿详情
    public function simulatecon(){
        $cwho['parentid']=$_POST['parentid'];
        $parent=M('parent')->where($cwho)->find();

        //student表
        $swhe['parentcard']=$parent['parentcard'];
        $student=M('student')->where($swhe)->find();
        $plannedWhere['t_d_user_planned.user_id']=$student['studentid'];
        $plannedWhere['t_d_user_planned.id']=I('post.id');
        $plannedData=M('d_user_planned')->join('t_d_batch ON t_d_user_planned.batchid = t_d_batch.id','LEFT')->where($plannedWhere)->field('t_d_user_planned.id,t_d_batch.name,t_d_batch.year,t_d_batch.batch_score,t_d_batch.remark')->select();
        $plannedUnivWhere['planned_id']=$plannedData[0]['id'];
        $plannedUniv=M('d_user_planned_univ')->where($plannedUnivWhere)->select();
        // $plannedData=M('d_user_planned')->join('t_d_batch ON t_d_user_planned.batchid = t_d_batch.id','LEFT')->join('t_d_user_planned_univ ON t_d_user_planned.id = t_d_user_planned_univ.planned_id','LEFT')->where($plannedWhere)->order('t_d_user_planned.id DESC')->limit($left,$yiye)->field("t_d_user_planned.id,t_d_user_planned.score,t_d_user_planned.create_date,t_d_batch.name,t_d_user_planned_univ.univID,t_d_user_planned_univ.univ_name,t_d_user_planned_univ.univ_seq,t_d_user_planned_univ.major_name,t_d_user_planned_univ.major_seq")->select();
        $plannedUnivs=array();
        foreach($plannedUniv as $k=>$v){
            $plannedUnivs[$v[univ_seq]]['univ_seq']=$v[univ_seq];
            $plannedUnivs[$v[univ_seq]]['univ_name']=$v[univ_name];
            $plannedUnivs[$v[univ_seq]]['str']=empty($plannedUnivs[$v[univ_seq]]['major_name'])?'':'、';
            $plannedUnivs[$v[univ_seq]]['major_name'].= $plannedUnivs[$v[univ_seq]]['str'].$v[major_name];
            $plannedUnivs[$v[univ_seq]]['obey']=$v[obey];
        }
        $newplan=array();
        $i=0;
        foreach($plannedUnivs as $k=>$v){
          $newplan[$i]=$v;
          $i++;

        }
        $data['plannedData']=$plannedData;
        $data['plannedUnivs']=$newplan;
        $this->apiReturn(100,'提交成功',$data);    
        

    }
}


