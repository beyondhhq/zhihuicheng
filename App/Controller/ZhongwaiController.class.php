<?php
namespace App\Controller;
use Think\Controller;
class ZhongwaiController extends DomainController {
	/*中外合作办学 资讯*/
    public function zixun(){
        $sqls = M('recruit_advisory');
        $wherea['plate'] = 7;
        $wherea['kind'] = 1;
        $advisory = $sqls->where($wherea)->field('id,name,time')->order('time desc')->select();
        $data=$advisory;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*中外合作办学 方略*/
    public function fanglue(){
        $sqls = M('recruit_advisory');
        $wherea['plate'] = 7;
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
    /*中外合作办学机构*/
    public function jigou(){
        $province['id'] = array('ELT','31');
        $info = M('d__recruit_zwhz')->field('id,province')->where($province)->select();

        $one['id']='32';
        $ones=M('d__recruit_zwhz')->field('id,province')->where($one)->select();

        $two['id']='33';
        $twos=M('d__recruit_zwhz')->field('id,province')->where($two)->select();

        $data=array(
            'lists'=>$info,
            'one'=>$ones,
            'two'=>$twos
        );

        $this->apiReturn(100,'读取成功',$data);
    }
    /*中外合作办学机构 详情*/
    public function detail(){
        $name['id'] = $_POST['id'];
        $info =M('d__recruit_zwhz')->where($name)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
  
}