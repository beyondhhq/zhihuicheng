<?php
namespace App\Controller;
use Think\Controller;
class ZhiyuantianbaoController extends DomainController{
  /*专家讲座*/
    public function zjjzlist()
    {
        $where['class'] = 2;
        $where['kind'] = 5;
        $info = M('video')->field('ViideoID,VideoName,image,time, VideocSrc')->where($where)->select();
        $this->apiReturn(100,'读取成功',$info);
    }

   
}

