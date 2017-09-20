<?php
namespace App\Controller;
use Think\Controller;
class ZizhuzhaoshengController extends DomainController {
	/*自主招生 资讯*/
    public function zixun(){
        $where['plate'] = 1;
        $where['kind'] = 1;
        $info = M('recruit_advisory')->where($where)->field('id,simple,name,time')->order('time desc')->select();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*自主招生 方略*/
    public function fanglue(){
        $wheres['plate'] = 1;
        $wheres['kind'] = 2;
        $info= M('recruit_advisory')->where($wheres)->order('time desc')->select();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*资讯和方略 详情*/
    public function infos(){
        $where['id'] = $_POST['id'];
        $info = M('recruit_advisory')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*自主招生 招生简章*/
    public function lists(){
        $where['year'] = '2017';
        $info = M('d__recruit_zzzs')->where($where)->select();
        $year=array('2017','2016','2015');
        $data=array(
            'year'=>$year,
            'lists'=>$info
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*自主招生 招生简章 检索*/
    public function year(){
        $where['year'] = $_POST['year'];
        $info = M('d__recruit_zzzs')->where($where)->select();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*自主招生 招生简章 详情*/
    public function detail(){
        $where['id'] = $_POST['id'];
        $info = M('d__recruit_zzzs')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }

}