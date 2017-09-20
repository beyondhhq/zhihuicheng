<?php
namespace App\Controller;
use Think\Controller;
class ZhuanyekuController extends DomainController {
    /*本科专业*/
    public function benke(){
        $sql = M();
        $one = $sql->query('select DISTINCT Main_category_id from t_d_major where kelei="本科" order by id asc'); //本科一大类

        $wtwo = '经济学（02）';
        $two=$sql->query("select DISTINCT Category_id from t_d_major where Main_category_id='$wtwo'");
        $info=$sql->query("select Category_id,id,Name from t_d_major where Main_category_id='$wtwo'"); //类别

        $data=array(
            'one'=>$one,
            'two'=>$two,
            'three'=>$info
        );
        $this->apiReturn(100,'读取成功',$data);

    }
    /*专业库*/
    public function navall(){
        $data=M('d_major')->order('id asc')->field('kelei,Main_category_id,Category_id,Name')->select();
        $this->apiReturn(100,'读取成功',$data);
    }
    /*点击本科一级专业名称展开内容*/
    public function menubenke(){
        $wtwo = $_POST['category'];//一级专业名称
        $sql = M();
        $two=$sql->query("select DISTINCT Category_id from t_d_major where Main_category_id='$wtwo'");
        $info=$sql->query("select Category_id,id,Name from t_d_major where Main_category_id='$wtwo'"); //类别

        $data=array(
            'twomenu'=>$two,
            'threemenu'=>$info
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    
    /*专科专业*/
    public function zhuanke(){
        $sql = M();
        $one = $sql->query('select DISTINCT Main_category_id from t_d_major where kelei="专科" order by id asc'); //专科一大类

        $wtwo = '51农林牧渔大类';
        $two=$sql->query("select DISTINCT Category_id from t_d_major where Main_category_id='$wtwo'");
        $info=$sql->query("select Category_id,id,Name from t_d_major where Main_category_id='$wtwo'"); //类别

        $data=array(
            'one'=>$one,
            'two'=>$two,
            'three'=>$info
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*点击专科一级专业名称展开内容*/
    public function menuzhuanke(){
        $wtwo = $_POST['category'];//一级专业名称
        $sql = M();
        $two=$sql->query("select DISTINCT Category_id from t_d_major where Main_category_id='$wtwo'");
        $info=$sql->query("select Category_id,id,Name from t_d_major where Main_category_id='$wtwo'"); //类别

        $data=array(
            'twomenu'=>$two,
            'threemenu'=>$info
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*查看专业*/
    public function infos(){
        $go=$_POST['name'];
        $where['Name']=$go;
        $info=M('d_major')->where($where)->find();
        $data1['Name']=$info['name'];//可从事岗位
        $data1['type']="可从事岗位";
        $data1['_logic'] = 'and';
        $jobs1=M('d_employment')->where($data1)->order('id asc')->select();
        $data2['Name']=$info['name'];//就业行业分布
        $data2['type']="就业行业分布";
        $data2['_logic'] = 'and';
        $jobs2=M('d_employment')->where($data2)->order('id asc')->select();
        $data3['Name']=$info['name'];//就业地区分布
        $data3['type']="就业地区分布";
        $data3['_logic'] = 'and';
        $jobs3=M('d_employment')->where($data3)->order('id asc')->select();
        $data4['Name']=$info['name'];//工资情况
        $data4['type']="工资情况";
        $data4['_logic'] = 'and';
        $jobs4=M('d_employment')->where($data4)->order('id asc')->select();
        $data5['Name']=$info['name'];//经验要求
        $data5['type']="经验要求";
        $data5['_logic'] = 'and';
        $jobs5=M('d_employment')->where($data5)->order('id asc')->select();
        $data6['Name']=$info['name'];//学历要求
        $data6['type']="学历要求";
        $data6['_logic'] = 'and';
        $jobs6=M('d_employment')->where($data6)->order('id asc')->select();

        $number['number'] = $info['number']+1;
        $number=M('d_major')->where($where)->save($number);
        $major['major'] = $info['name'];
        $data = M('d_major_rank_wsl')->where($major)->limit(20)->select();

        $data=array(
            'infos'=>$info,
            'kecongshigangnwei'=>$jobs1,//可从事岗位
            'jiuyehangyefenbu'=>$jobs2,//就业行业分布
            'jiuyediqufenbu'=>$jobs3,//就业地区分布
            'gongziqingkuang'=>$jobs4,//工资情况
            'jingyanyaoqiu'=>$jobs5,//经验要求
            'xueliyaoqiu'=>$jobs6, //学历要求
            'data'=>$data
        );
        $this->apiReturn(100,'读取成功',$data);
    }

}