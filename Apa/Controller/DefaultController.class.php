<?php
namespace Apa\Controller;
use Think\Controller;
class DefaultController extends DomainController {
    /*资讯动态*/
    public function hotzixun(){
    	$where['kind'] = '3';       
        $Data = M('p_volunteer_encyclopedia');
        $qg = $Data->where($where)->field('id,time,name')->order('time desc')->limit(1)->select(); // 全国资讯

        $wheres['kind'] = 2;
        $sql = M('p_volunteer_encyclopedia');
        $zx = $sql->where($wheres)->field('id,name,time')->order('time desc')->limit(1)->select(); //最新资讯

        $data=array(
            'quanguozixun'=>$qg,
            'zuixinzixun'=>$zx
        );

		$this->apiReturn(100,'读取成功',$data);
    }
    /*高考新政*/
    public function gaokaoxinzheng(){
        $parentid=$_POST['parentid'];

        $cwho['parentid']=$parentid;
        $cinfo=M('parent')->where($cwho)->find();
        //县
        $cxian['ProvincesID']=$cinfo['xian'];
        $cxian=M('provinces')->where($cxian)->find();
        //市
        $cshi['ProvincesID']=$cxian['pid'];
        $cshi=M('provinces')->where($cshi)->find();
        //省
        $csheng['ProvincesID']=$cshi['pid'];
        $csheng=M('provinces')->where($csheng)->find();
        $sheng=$csheng['provincesname'];
        $s=mb_substr($sheng,0,2,'utf-8');//当前省

        $province['province'] = array('like',"%{$s}%");
        $data=M('policy')->where($province)->select();
        $this->apiReturn(100,'读取成功',$data);
    }
    /*推荐阅读*/
    public function tuijian(){
        $sql = M('parent_jyks');
        $one1['kind'] = '1';
        $one1['class'] = '1';
        $one =$sql->where($one1)->field('id,name,sample,time,pict')->limit(1)->order('time desc')->select();
        foreach ($one as $k => $v) {
            $one[$k]['category']='品德养成';
        }

        $one2['kind'] = '1';
        $one2['class'] = '2';
        $two =$sql->where($one2)->field('id,name,sample,time,pict')->limit(1)->order('time desc')->select();
        foreach ($two as $k => $v) {
            $two[$k]['category']='学业指导';
        }
        $one3['kind'] = '1';
        $one3['class'] = '3';
        $three =$sql->where($one3)->field('id,name,sample,time,pict')->limit(1)->order('time desc')->select();
        foreach ($three as $k => $v) {
            $three[$k]['category']='社会实践';
        }
        $one4['kind'] = '2';
        $one4['class'] = '1';
        $four =$sql->where($one4)->field('id,name,sample,time,pict')->limit(1)->order('time desc')->select();
        foreach ($four as $k => $v) {
            $four[$k]['category']='心理健康';
        }
        $one5['kind'] = '2';
        $one5['class'] = '2';
        $five =$sql->where($one5)->field('id,name,sample,time,pict')->limit(1)->order('time desc')->select();
        foreach ($five as $k => $v) {
            $five[$k]['category']='健康饮食';
        }
        $one6['kind'] = '2';
        $one6['class'] = '3';
        $six =$sql->where($one6)->field('id,name,sample,time,pict')->limit(1)->order('time desc')->select();
        foreach ($six as $k => $v) {
            $six[$k]['category']='劳逸结合';
        }
        $one7['kind'] = '3';
        $one7['class'] = '1';
        $seven =$sql->where($one7)->field('id,name,sample,time,pict')->limit(1)->order('time desc')->select();
        foreach ($seven as $k => $v) {
            $seven[$k]['category']='成功之路';
        }
        $one8['kind'] = '3';
        $one8['class'] = '2';
        $eight =$sql->where($one8)->field('id,name,sample,time,pict')->limit(1)->order('time desc')->select();
        foreach ($eight as $k => $v) {
            $eight[$k]['category']='前车之鉴';
        }
        $one9['kind'] = '3';
        $one9['class'] = '3';
        $nine =$sql->where($one9)->field('id,name,sample,time,pict')->limit(1)->order('time desc')->select();
        foreach ($nine as $k => $v) {
            $nine[$k]['category']='习惯养成';
        }
        $data=array(
            'one'=>$one,
            'two'=>$two,
            'three'=>$three,
            'four'=>$four,
            'five'=>$five,
            'six'=>$six,
            'seven'=>$seven,
            'eight'=>$eight,
            'nine'=>$nine
        );
        $this->apiReturn(100,'读取成功',$data);
    }

}