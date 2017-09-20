<?php
namespace App\Controller;
use Think\Controller;
class GaozhongzhiduController extends DomainController {
    /*高中制度*/
    public function lists(){
        $shang['province'] = '上海';
        $shanghai = M('da_high_school')->where($shang)->field('title,id')->select();
        
        $shejiang['province'] = '浙江';
        $zhejiang = M('da_high_school')->where($shejiang)->field('title,id')->select();

        $data=array(
        'shanghai'=>$shanghai,
        'zhejiang'=>$zhejiang
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*查看高中制度*/
    public function infos(){
        $where['id'] = $_POST['id'];
        $info = M('da_high_school')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }

}