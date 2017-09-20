<?php
namespace App\Controller;
use Think\Controller;
class GaoxiaofanganController extends DomainController {
    /*高校方案*/
    public function lists(){
        $sql = M('');
        $province = $sql->query("select distinct RecruitProvince from t_d__recruit_zhpj where year='2017' order by instr(',全国,辽宁,上海,江苏,浙江,山东,广东,',concat(',',RecruitProvince,','))");

        $wher['RecruitProvince'] = '全国';
        $wher['year'] = 2017;
        $school  = M('d__recruit_zhpj')->where($wher)->field('dxmc')->select();

        $where['year'] = 2017;
        $where['dxmc'] = '北京大学';
        $where['RecruitProvince'] = '全国';
        $where['_logic'] = 'and';

        $name['dxmc'] = '北京大学';
        $name['RecruitProvince'] = '全国';
        $name['_logic'] = 'and';
        $info = M('d_university')->where($name)->field('dxmc,picture')->find();
        $datas = M('d__recruit_zhpj')->where($where)->find();
        $datac = array_merge($info,$datas);
        
        $data=array(
        'province'=>$province,
        'school'=>$school,
        'infos'=>$datac
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*通过地区检索学校*/
    public function searchs(){

        $wher['RecruitProvince'] = $_POST['province'];
        $wher['year'] = 2017;
        $school = M('d__recruit_zhpj')->where($wher)->field('dxmc')->select();
        

        $where['year'] = 2017;
        $where['dxmc'] = $_POST['school'];
        $where['RecruitProvince'] = $_POST['province'];
        $where['_logic'] = 'and';

        $name['dxmc'] = $_POST['school'];
        $name['RecruitProvince'] = $_POST['province'];
        $name['_logic'] = 'and';
        $info = M('d_university')->where($name)->field('dxmc,picture')->find();
        $datas = M('d__recruit_zhpj')->where($where)->find();
        $datac = array_merge($info,$datas);

        $data=array(
        'school'=>$school,
        'infos'=>$datac
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*查看高校方案*/
    public function infos(){
        $where['id'] = $_POST['id'];
        $info = M('d__recruit_zhpj')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }

}