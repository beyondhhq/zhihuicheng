<?php
namespace App\Controller;
use Think\Controller;
class GaokaoxinzhengController extends DomainController {
    public function tuijian(){
            
            $provincename=$_POST['sheng']?$_POST['sheng']:'北京';
            
            if($provincename=="新疆维吾尔自治区"){
               $newprovincename="新疆";
            }elseif($provincename=="西藏自治区"){
               $newprovincename="西藏";
            }elseif($provincename=="宁夏回族自治区"){
               $newprovincename="宁夏";
            }elseif($provincename=="广西壮族自治区"){
               $newprovincename="广西";
            }elseif($provincename=="内蒙古自治区"){
               $newprovincename="内蒙古";
            }elseif($provincename=="香港特别行政区"){
               $newprovincename="港澳";
            }elseif($provincename=="澳门特别行政区"){
               $newprovincename="港澳";
            }else{
               $newprovincename=mb_substr($provincename,0,2,'utf8' );
            }
            $where['province'] = $newprovincename;
            $where['kind'] = 2;
            $infos = M('p_volunteer_encyclopedia_two')->field('name')->where($where)->order('time desc')->find();
            $data[]="国务院关于深化考试招生制度改革的实施意见";
            $data[]=$infos['name'];
            $this->apiReturn(100,'读取成功',$data);
    }
    /*高考新政*/
    public function lists(){
        $province=array('北京','天津','河北','山西','内蒙古','辽宁','吉林','黑龙江','上海','江苏','浙江','安徽','福建','江西','山东','河南','湖北','湖南','广东','广西','海南','重庆','四川','贵州','云南','西藏','陕西','甘肃','青海','宁夏','新疆');
        if($_POST['province']){
            $str=$_POST['province'];
            $pro=mb_substr($str,0,2,'utf-8');
            $where['province'] = array('like',"%$pro");
            $lists=M('policy')->where($where)->select();       
        }else{
            $lists=M('policy')->select();
        }

        $data=array(
            'aa'=>$pro,
        'province'=>$province,
        'lists'=>$lists
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*查看省份新政*/
    public function infos(){
        $where['url'] = $_POST['url'];
        $sql = M('policy');
        $info = $sql->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }

}