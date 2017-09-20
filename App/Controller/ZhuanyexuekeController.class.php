<?php
namespace App\Controller;
use Think\Controller;
class ZhuanyexuekeController extends DomainController {
	/*专业学科对照列表*/
    public function lists(){
        //省份
        $sql =M();
        $province=$sql->query("select DISTINCT province from t_d_subject_choice order by id ASC");
        //学历
        $xueli=array('本科','专科');
        //北京的大学
        $where['province']='北京';
        $school=M('d_university')->where($where)->select();
        //北京的本科大学
        foreach($school as $k=>$v){
            $schools.=$v['dxmc'].',';
        }
        $schools=explode(',',$schools);
        $subject['dxmc']=array('in',$schools);
        $subject['xlcc']=array("like","本科%");
        $subject['_logic'] = 'and';
        $schools=M('d_subject_choice')->where($subject)->group('dxmc')->field('id,dxmc,province,xlcc,gxdm')->select();
        foreach ($schools as $k=> $v) {
            $why['gxdm']=$v['gxdm'];
            $img=M('d_university')->where($why)->find();
            $schools[$k]['logo']=$img['logo'];
        }
    	$data=array(
        'province'=>$province,
        'xueli'=>$xueli,
        'schools'=>$schools
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*专业学科对照列表检索*/
    public function search(){
        $subject['province']=$_POST['province'];
        $subject['xlcc']=array("like","%{$_POST['xueli']}%");
        $subject['_logic'] = 'and';
        $schools=M('d_subject_choice')->where($subject)->group('dxmc')->field('id,dxmc,province,xlcc,gxdm')->select();
        foreach ($schools as $k=> $v) {
            $why['gxdm']=$v['gxdm'];
            $img=M('d_university')->where($why)->find();
            $schools[$k]['logo']=$img['logo'];
        }
        $data=$schools;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*查看大学专业学科详情*/
    public function infos(){
        $xueli=$_POST['xueli'];//学历
        $school=$_POST['school'];//学校
        //大学信息
        $where['xlcc']=array("like","%{$xueli}%");
        $where['dxmc']=$school;
        $where['year']='2018';
        $where['Type']='1';
        $where['_logic'] = 'and';
        $info=M('d_subject_choice')->where($where)->find();
        $which['dxmc']=$info['dxmc'];
        $infos=M('d_university')->where($which)->find();
        //专业选考信息
        $sub['dxmc']=$school;
        $sub['year']='2018';
        $sub['Type']='1';
        $sub['_logic'] = 'and';
        $lists=M('d_subject_choice')->where($sub)->select();

        $data=array(
        'school'=>$infos,
        'lists'=>$lists
        );
        $this->apiReturn(100,'读取成功',$data);
    }

}