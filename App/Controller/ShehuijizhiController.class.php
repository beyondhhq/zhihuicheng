<?php
namespace App\Controller;
use Think\Controller;
class ShehuijizhiController extends DomainController {
    /*社会机制*/
    public function lists(){
        $zhiyuan['class'] = 2;
        $zhi = M('da_society')->where($zhiyuan)->field('title,id')->select();
        $jingsai['class'] = 1;
        $jing = M('da_society')->where($jingsai)->field('title,id,kind')->select();
        $newjing=array();
        foreach($jing as $k=>$v){
             if($v['kind']==2){
                $newjing[$v['kind']-1]['title']='理科类竞赛';
                $newjing[$v['kind']-1]['lists'][]=$v;
             }
             if($v['kind']==1){
    
                $newjing[$v['kind']-1]['title']='文科类竞赛';
                $newjing[$v['kind']-1]['lists'][]=$v;

             }
             
             if($v['kind']==3){
               
                $newjing[$v['kind']-1]['title']='科技创新类竞赛';
                $newjing[$v['kind']-1]['lists'][]=$v;

             }
             if($v['kind']==4){
                
                $newjing[$v['kind']-1]['title']='艺术类竞赛';
                $newjing[$v['kind']-1]['lists'][]=$v;

             }
             if($v['kind']==5){
             
                $newjing[$v['kind']-1]['title']='体育类竞赛';
                $newjing[$v['kind']-1]['lists'][]=$v;

             }
             if($v['kind']==6){
               
                $newjing[$v['kind']-1]['title']='其它类竞赛';
                $newjing[$v['kind']-1]['lists'][]=$v;

             }


             

        }
        $newjings=array();
        $a=0;
        foreach($newjing as $k=>$v){
            $newjings[$a]=$v;
            $a++;
        }  
        $data=array(
        'zhiyuan'=>$zhi,
        'jingsai'=>$newjings
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*查看社会机制*/
    public function infos(){
        $where['id'] = $_POST['id'];
        $info = M('da_society')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }

}