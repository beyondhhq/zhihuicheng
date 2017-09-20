<?php
namespace App\Controller;
use Think\Controller;
class GaozhiController extends DomainController {
	/*高职单招 资讯*/
    public function zixun(){
        $sqls = M('recruit_advisory');
        $wherea['plate'] = 8;
        $wherea['kind'] = 1;
        $advisory = $sqls->where($wherea)->field('id,name,time')->order('time desc')->select();
        $data=$advisory;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*高职单招 方略*/
    public function fanglue(){
        $sqls = M('recruit_advisory');
        $wherea['plate'] = 8;
        $wherea['kind'] = 2;
        $advisory = $sqls->where($wherea)->field('id,name,time')->order('time desc')->select();
        $data=$advisory;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*资讯和方略 详情*/
    public function infos(){
        $where['id'] = $_POST['id'];
        $info = M('recruit_advisory')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*高职院校 招生简章*/
    public function jianzhang(){
        $sql=M('');
        $province=$sql->query("select DISTINCT province from t_d__recruit_gzdz order by instr(',北京,天津,河北,山西,内蒙古,辽宁,吉林,黑龙江,上海,江苏,浙江,安徽,福建,江西,山东,河南,湖北,湖南,广东,广西,海南,重庆,四川,贵州,云南,陕西,甘肃,青海,宁夏,新疆,',concat(',',province,','))");

        $xuelicc=array('专科','本科');
        $xingzhi=array('公办','民办');

        $where['province'] ="北京";
        $where['xlcc'] ="专科";
        $where['bxxz'] ="公办";
        $school=M('d__recruit_gzdz')->where($where)->field('id,univ_name')->select();
        $whereinfo['t_d__recruit_gzdz.id'] = 479;
        $info = M('d__recruit_gzdz')->where($whereinfo)->join('t_d_university b on t_d__recruit_gzdz.univ_name=b.dxmc')->field('t_d__recruit_gzdz.id,t_d__recruit_gzdz.univ_name,t_d__recruit_gzdz.xlcc,t_d__recruit_gzdz.bxxz,t_d__recruit_gzdz.theme,b.picture')->find();

        $data=array(
            'province'=>$province,
            'xlcc'=>$xuelicc,
            'xingzhi'=>$xingzhi,
            'school'=>$school,
            'info'=>$info
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*高职院校 招生简章 检索*/
    public function searchs(){
        if($_POST['province']){
            $where['province'] =$_POST['province'];
        }
        if($_POST['xlcc']){
            $where['xlcc'] =$_POST['xlcc'];
        }
        if($_POST['bxxz']){
            $where['bxxz'] =$_POST['bxxz'];          
        }
        if($_POST['province']){
            $wher['t_d__recruit_gzdz.province'] =$_POST['province'];
        }
        if($_POST['xlcc']){
            $wher['t_d__recruit_gzdz.xlcc'] =$_POST['xlcc'];
        }
        if($_POST['bxxz']){
            $wher['t_d__recruit_gzdz.bxxz'] =$_POST['bxxz'];          
        }
        if($_POST['id']){
            $wher['t_d__recruit_gzdz.id'] =$_POST['id'];          
        }
        $school=M('d__recruit_gzdz')->where($where)->field('id,univ_name')->select();
        $firschool=M('d__recruit_gzdz')->where($where)->field('id,univ_name')->find();
        $whereinfo['t_d__recruit_gzdz.id'] = $firschool['id'];
        $info = M('d__recruit_gzdz')->where($wher)->join('t_d_university b on t_d__recruit_gzdz.univ_name=b.dxmc')->field('t_d__recruit_gzdz.id,t_d__recruit_gzdz.univ_name,t_d__recruit_gzdz.xlcc,t_d__recruit_gzdz.bxxz,t_d__recruit_gzdz.theme,b.picture')->find();
        $moreninfo = M('d__recruit_gzdz')->where($whereinfo)->join('t_d_university b on t_d__recruit_gzdz.univ_name=b.dxmc')->field('t_d__recruit_gzdz.id,t_d__recruit_gzdz.univ_name,t_d__recruit_gzdz.xlcc,t_d__recruit_gzdz.bxxz,t_d__recruit_gzdz.theme,b.picture')->find();
        if($_POST['id']){
          $data['school']=$school;
          $data['info']=$info;
        }else{     
          $data['school']=$school;
          $data['info']=$moreninfo;
        }
        
        $this->apiReturn(100,'读取成功',$data);
    }
    /*高职院校 招生简章 详情*/
    public function detail(){
        $where['id'] = $_POST['id'];
        $info = M('d__recruit_gzdz')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
}