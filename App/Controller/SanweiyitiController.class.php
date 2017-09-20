<?php
namespace App\Controller;
use Think\Controller;
class SanweiyitiController extends DomainController {
	/*三位一体 资讯*/
    public function zixun(){
        $sql = M('recruit_advisory');
        $wherea['plate'] = 2;
        $wherea['kind'] = 1;
        $advisory = $sql->where($wherea)->order('time desc')->select();
        $data=$advisory;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*三位一体 方略*/
    public function fanglue(){
        $sql = M('recruit_advisory');
        $where['plate'] = 2;
        $where['kind'] = 2;
        $general = $sql->where($where)->order('time desc')->select();
        $data=$general;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*资讯和方略 详情*/
    public function infos(){
        $where['id'] = $_POST['id'];
        $info = M('recruit_advisory')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*三位一体 招生简章*/
    public function lists(){
        $pro = M();
        $one = $pro->query("select DISTINCT RecruitProvince from t_d__recruit_zhpj where year='2017' order by instr(',全国,辽宁,上海,江苏,浙江,山东,广东,',concat(',',RecruitProvince,','))"); //省份

        $category="全国";
        $nian="2017"; 
        $where['RecruitProvince']=$category;
        $where['year']=$nian;
        $sql=M('d__recruit_zhpj');
        $info=$sql->where($where)->order('id asc')->select();
        $year=array('2017','2016','2015');
        $data=array(
            'year'=>$year,
            'province'=>$one,
            'lists'=>$info
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*三位一体 招生简章 检索*/
    public function year(){
        $category=$_POST['province'];
        $nian=$_POST['year'];
        $where['RecruitProvince']=$category;
        $where['year']=$nian;
        $sql=M('d__recruit_zhpj');
        $info=$sql->where($where)->order('id asc')->select();

        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*三位一体 招生简章 详情*/
    public function detail(){
        $where['id'] = $_POST['id'];
        $info = M('d__recruit_zhpj')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }

}