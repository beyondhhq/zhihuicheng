<?php
namespace Apa\Controller;
use Think\Controller;
class JiazhangxuexiaoController extends DomainController {
	/*专题讲座*/
    public function zhuantijz(){
        $sql = M('parent_ztjz');
        $where['class'] = '1';
        //$where['id']='99999999999999';//暂时空数组处理
        $data =$sql->where($where)->order('time desc')->select();
        foreach ($data as $k => $v) {
            $data[$k]['link']="http://www.yxke12.com/Public/Parent/images/zhuantijiangzuo/".$v['url'];
        }
        $this->apiReturn(100,'读取成功',$data);
    }
    /*专题讲座 详情页*/
     public function infos(){
        $where['id']=$_POST['id'];
        $where['class']='1';
        $data=M('parent_ztjz')->where($where)->order('id desc')->find();
        $this->apiReturn(100,'读取成功',$data);
     }
    /*智慧学堂1*/
    public function zhxt(){
        $sql = M('parent_ztjz');
        $where['class'] = '2';
        $data =$sql->where($where)->field('id,title,sample,time,pic')->order('time desc')->select();
        $this->apiReturn(100,'读取成功',$data);
    }
    /*家长书架2*/
    public function jzsj(){
        $sql = M('parent_ztjz');
        $where['class'] = '3';
        $data=$sql->where($where)->field('id,title,sample,time,pic')->order('time desc')->select();
        $this->apiReturn(100,'读取成功',$data);
    }
    /*海外之窗3*/
    public function hwzc(){
        $sql = M('parent_ztjz');
        $where['class'] = '4';
        $data =$sql->where($where)->field('id,title,sample,time,pic')->order('time desc')->select();
        $this->apiReturn(100,'读取成功',$data);
    }
    /*123详情*/
    public function detail(){
        $id = $_POST['id'];
        $where['id'] = $id;
        $data = M('parent_ztjz')->where($where)->find();
        $this->apiReturn(100,'读取成功',$data);
    }
    
}