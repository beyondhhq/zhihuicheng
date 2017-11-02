<?php
namespace App\Controller;
use Think\Controller;
class ShengyaketangController extends DomainController {
    /*生涯课堂*/
    public function lists(){
        $title1='适合的教育是最好的教育';
        $where1['class']='1';
        $where1['kind']='1';
        $info1=M('video')->where($where1)->order('time asc')->select();
        $title2='怎样选科选考';
        $where2['class']='3';
        $where2['kind']='1';
        $info2=M('video')->where($where2)->order('time asc')->select();
        $title3='怎样做好人生规划';
        $where3['class']='4';
        $where3['kind']='1';
        $info3=M('video')->where($where3)->order('time asc')->select();
        $data=array(
            'title1'=>$title1,
            'info1'=>$info1,
            'title2'=>$title2,
            'info2'=>$info2,
            'title3'=>$title3,
            'info3'=>$info3
        );
        $this->apiReturn(100,'请求成功',$data);
    } 
    /*推荐-生涯课堂最新3条数据*/
    public function hotvideo(){
        $where1['class']='1';
        $where1['kind']='1';
        $where2['class']='3';
        $where2['kind']='1';
        $where3['class']='4';
        $where3['kind']='1';
        $data1=M('video')->order('time asc')->where($where1)->limit(1)->select();
        $data2=M('video')->order('time asc')->where($where2)->limit(1)->select();
        $data3=M('video')->order('time asc')->where($where3)->limit(1)->select();
        $data=array_merge($data1,$data2,$data3);
        $this->apiReturn(100,'读取成功',$data);
    }
}