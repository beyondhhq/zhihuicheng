<?php
namespace App\Controller;
use Think\Controller;
class YishuleiController extends DomainController {
	/*艺术类 资讯*/
    public function zixun(){
        $sqls = M('recruit_advisory');
        $wherea['plate'] = 3;
        $wherea['kind'] = 1;
        $advisory = $sqls->where($wherea)->order('time desc')->select();
        $data=$advisory;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*艺术类 方略*/
    public function fanglue(){
        $sqls = M('recruit_advisory');
        $wherea['plate'] = 3;
        $wherea['kind'] = 2;
        $advisory = $sqls->where($wherea)->order('time desc')->select();
        $data=$advisory;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*资讯和方略 详情*/
    public function infos(){
        $where['id'] = $_POST['id'];
        $info = M('recruit_advisory')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*非独立艺术院校 招生简章*/
    public function feiduli(){
        $sql=M('d__recruit_art');
        $info=$sql->select();
        
        foreach($info as $k=>$v){
            if(($v['province'] != '独立设置的本科艺术院校(31所)')){
                if(($v['province'] != '参照独立设置本科艺术院校招生高校名单(15所)')){
                $provinces[$k] = $v['province'];
            }}
        }
        $provinces = array_unique($provinces);

        // foreach($info as $k=>$v){
        //     if($v['province'] == '北京'){
        //     $infos[$k]['univ_name'] = $v['univ_name'];
        //     $infos[$k]['id'] = $v['id'];    
        //     $infos[$k]['univ_id'] = $v['univ_id'];  
        //     }
        // }
        $where['province']='北京';
        $infos=M('d__recruit_art')->where($where)->select();

        $data=array(
            'province'=>$provinces,
            'lists'=>$infos
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*非独立艺术院校 招生简章 检索*/
    public function province(){
        $where['province']=$_POST['province'];
        $datas=M('d__recruit_art')->where($where)->order('id asc')->field('id,univ_name')->select();
        $data=$datas;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*非独立艺术院校 招生简章 详情*/
    public function detail(){
        $where['id'] = $_POST['id'];
        $info = M('d__recruit_art')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*高水平艺术团 招生简章*/
    public function yishutuan(){
        $pro=M();
        $proinfos = $pro->query("select DISTINCT province from t_d__recruit_art_sp where year='2017' order by instr(',北京,天津,辽宁,吉林,黑龙江,上海,江苏,浙江,福建,山东,湖北,湖南,广东,重庆,四川,陕西,',concat(',',province,','))"); //省份
        $datas=M('d__recruit_art_sp')->field('id,year,province,univ_id,univ_name,theme,bm_time,major')->select();
        $data=array(
            'province'=>$proinfos,
            'lists'=>$datas
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*高水平艺术团 招生简章 检索*/
    public function yishutuansearch(){
        $where['province']=$_POST['province'];
        $info=M('d__recruit_art_sp')->field('id,year,province,univ_id,univ_name,theme,bm_time,major')->where($where)->select();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*高水平艺术团 招生简章 详情*/
    public function yishudetail(){
        $where['id'] = $_POST['id'];
        $info = M('d__recruit_art_sp')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*独立艺术院校 招生简章 31所*/
    public function dulione(){
        $sql=M('d__recruit_art');
        $where['independent']=1;
        $info = $sql->where($where)->select();
        foreach($info as $k=>$v){
            if($v['province'] =='独立设置的本科艺术院校(31所)'){
                $infos[$k]['univ_name'] = $v['univ_name'];
                $infos[$k]['id'] = $v['id'];
            }
        }
        $data=$infos;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*独立艺术院校 招生简章 13所*/
    public function dulitwo(){
        $sql=M('d__recruit_art');
        $where['independent']=1;
        $info = $sql->where($where)->select();
        // foreach($info as $k=>$v){
        //     if($v['province'] =='参照独立设置本科艺术院校招生高校名单(15所)'){
        //         $infoc[$k]['univ_name'] = $v['univ_name'];
        //         $infoc[$k]['id'] = $v['id'];
        //     }
        // }
        $wheres['province']='参照独立设置本科艺术院校招生高校名单(15所)';
        $infoc=M('d__recruit_art')->where($wheres)->field('id,univ_name')->select();

        $data=$infoc;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*独立艺术院校 招生简章 31所和13所 详情*/
    public function details(){
        $where['id'] = $_POST['id'];
        $info = M('d__recruit_art')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
}