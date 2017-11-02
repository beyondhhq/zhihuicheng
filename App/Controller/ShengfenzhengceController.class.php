<?php
namespace App\Controller;
use Think\Controller;
class ShengfenzhengceController extends DomainController {
    /*省份政策*/
    public function lists(){
        $teacherid=$_POST['teacherid'];
        $studentid=$_POST['studentid'];

        if($teacherid){
           $cwho['TeacherID']=$teacherid;
           $cinfo=M('teacher')->where($cwho)->find();
           //县
           $cxian['ProvincesID']=$cinfo['xian'];
           $cxians=M('provinces')->where($cxian)->find();

           //市
           $cshi['ProvincesID']=$cxians['pid'];
           $cshi=M('provinces')->where($cshi)->find();

           //省
           $csheng['ProvincesID']=$cshi['pid'];
           $csheng=M('d_province')->where($csheng)->find();
           $name = $csheng['provincesname'];
           if($name=='北京'||$name=='上海'||$name=='天津'||$name=='重庆'){
              $province=$name.'市';

           }elseif($name=='西藏'||$name=='内蒙古'){
              $province=$name.'自治区';

           }elseif($name=='新疆'){
              $province=$name.'维吾尔自治区';

           }elseif($name=='宁夏'){
              $province=$name.'回族自治区';

           }elseif($name=='广西'){
              $province=$name.'壮族自治区';

           }else{
              $province=$name.'省';

           }

        }
        if($studentid){

           $swho['StudentID']=$studentid;
           $cinfo=M('student')->where($swho)->find();
           //县
           $cxian['ProvincesID']=$cinfo['xian'];
           $cxians=M('provinces')->where($cxian)->find();
           //市
           $cshi['ProvincesID']=$cxians['pid'];
           $cshi=M('provinces')->where($cshi)->find();
           //省
           $csheng['ProvincesID']=$cshi['pid'];
           $csheng=M('d_province')->where($csheng)->find();
           $name = $csheng['provincesname'];
           if($name=='北京'||$name=='上海'||$name=='天津'||$name=='重庆'){
              $province=$name.'市';

           }elseif($name=='西藏'||$name=='内蒙古'){
              $province=$name.'自治区';

           }elseif($name=='新疆'){
              $province=$name.'维吾尔自治区';

           }elseif($name=='宁夏'){
              $province=$name.'回族自治区';

           }elseif($name=='广西'){
              $province=$name.'壮族自治区';

           }else{
              $province=$name.'省';

           }

        }
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