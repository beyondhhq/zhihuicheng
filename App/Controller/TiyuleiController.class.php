<?php
namespace App\Controller;
use Think\Controller;
class TiyuleiController extends DomainController {
	/*体育类 资讯*/
    public function zixun(){
        $sqls = M('recruit_advisory');
        $wherea['plate'] = 5;
        $wherea['kind'] = 1;
        $advisory = $sqls->where($wherea)->order('time desc')->limit('3')->select();
        $data=$advisory;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*体育类 方略*/
    public function fanglue(){
        $sqls = M('recruit_advisory');
        $wherea['plate'] = 5;
        $wherea['kind'] = 2;
        $advisory = $sqls->where($wherea)->order('time desc')->select();
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
    /*2017运动训练专业 招生简章*/
    public function yundong(){
        $major['major'] = '运动训练专业';
        $info =M('d__recruit_sport')->order('ID DESC')->where($major)->field('id,univ_name')->select();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
     /*2017武术与民族专业 招生简章*/
    public function wushu(){
        $major['major'] = '武术与民族传统体育专业';
        $info =M('d__recruit_sport')->order('ID DESC')->where($major)->field('id,univ_name')->select();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*2017运动训练专业 武术与民族专业 招生简章 详情*/
    public function details(){
        $where['id'] = $_POST['id'];
        $info = M('d__recruit_sport')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*2017高水平运动员 招生简章*/
    public function gaoshuiping(){
        $sql=M('d__recruit_sport_sp');
        $where['province'] = '北京';
        $info = $sql->where($where)->field('id,year,province,univ_id,univ_name,theme,bm_time,major')->select();

        $province = $sql->query("select DISTINCT province from t_d__recruit_sport_sp");

        $data=array(
            'province'=>$province,
            'lists'=>$info
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*2017高水平运动员 招生简章 检索*/
    public function province(){
        $where['province']=$_POST['province'];
        $info=M('d__recruit_sport_sp')->where($where)->field('id,year,province,univ_id,univ_name,theme,bm_time,major')->order('id asc')->select();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*2017高水平运动员 招生简章 详情*/
    public function gaoinfos(){
        $where['id'] = $_POST['id'];
        $info = M('d__recruit_sport_sp')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
}