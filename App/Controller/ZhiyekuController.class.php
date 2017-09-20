<?php
namespace App\Controller;
use Think\Controller;
class ZhiyekuController extends DomainController {
    /*职业库*/
    public function lists(){
        $pro = M();
        //一级
        $starters = $pro->query('select DISTINCT Main_category from t_d_job');

        $data=array(
            'one'=>$starters
        );
        $this->apiReturn(100,'读取成功',$data);

    }
    /*点击一级职业名称展开内容*/
    public function menuzhiye(){
        $main = $_POST['category'];
        $sql=M('');
        $infos=$sql->query("select Category,job,id from t_d_job where Main_category='$main'");
        foreach($infos as $k=>$v){
            foreach($infos as $ki=>$vi){
                if($v['job'] == $vi['job']){
                    $data[$v['category']][] = $v;
                }
            }
            
        }
        $data=$data;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*查看职业*/
    public function infos(){
        $go = $_POST['name'];
        $where['job']=$go;
        $info = M('d_job')->where($where)->find();
        $number['number'] = $info['number']+1;
        $string = $info['detail'];
        $array = explode("@",$string);
        M('d_job')->where($where)->save($number);
        
        $this->assign('info',$info);
        $this->assign('arr',$array);

        $data=array(
            'content'=>$info,
            'lists'=>$array
        );
        $this->apiReturn(100,'读取成功',$data);

    }

}