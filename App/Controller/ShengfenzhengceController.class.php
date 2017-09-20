<?php
namespace App\Controller;
use Think\Controller;
class ShengfenzhengceController extends DomainController {
    /*省份政策*/
    public function lists(){
        $province=$_POST['province'];
        $sqlp = M('');
        $info =$sqlp->query("select province from t_da_province_zhengce  order by instr(',北京市,天津市,河北省,山西省,内蒙古自治区,辽宁省,吉林省,黑龙江省,上海市,江苏省,浙江省,安徽省 ,福建省,江西省,山东省,河南省,湖北省,湖南省,广东省,广西自治区,海南省,重庆市,四川省,贵州省,云南省,西藏自治区,陕西省,甘肃省,青海省,宁夏自治区,新疆自治区,',concat(',',province,','))");
        if($province){
            $sheng['ProvincesName']=$province;
        }else{
            $sheng['ProvincesName']='北京市';
        }
        $shengfen=M('provinces')->where($sheng)->find();
        if($province){
            $zhengce['province']=$province;
        }else{
            $zhengce['province']='北京市';
        }
        $zhengce=M('da_province_zhengce')->where($zhengce)->find();

        $data=array(
        'province'=>$info,
        'shengfen'=>$shengfen,
        'zhengce'=>$zhengce
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*查看省份政策*/
    public function infos(){
        $where['id']=$_POST['id'];
        $info=M('da_province_zhengce')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }

}