<?php
namespace App\Controller;
use Think\Controller;
class YuanxiaokuController extends DomainController {
    /*院校列表*/
    public function lists(){
        $studentid=$_POST['studentid'];

        $sql =M();
        $province=$sql->query("select DISTINCT province from t_d_university");
        $yxlx=$sql->query("select DISTINCT yxlx from t_d_university");
        $xlcc=$sql->query("select DISTINCT xlcc from t_d_university");
        $yxts=array("0"=>"985工程","1"=>"211工程","2"=>"教育部直属");
        //院校列表
        $sql=M('d_university');//大学表
        $sqls = M('collect');  //收藏表

        $datas = $sql->order('rank asc')->limit(100)->select();

        $where['studentid'] = $studentid;
        $where['schoolid'] = array('neq','');
        $info = $sqls->where($where)->select();
    
        if($info){
            foreach($datas as $k=>$v){
                foreach($info as $ki=>$vi){
                    if($v['id'] == $vi['schoolid']){
                        $data[$k]['imgname'] = 1;
                        $data[$k]['id'] = $v['id'];
                        $data[$k]['province'] = $v['province'];
                        $data[$k]['logo'] = $v['logo'];
                        $data[$k]['dxmc'] = $v['dxmc'];
                        $data[$k]['zgbm'] = $v['zgbm'];
                        $data[$k]['is985'] = $v['is985'];
                        $data[$k]['is211'] = $v['is211'];
                        $data[$k]['iszizhu'] = $v['iszizhu'];
                        $data[$k]['ssd'] = $v['ssd'];
                        $data[$k]['gjzdxk'] = $v['gjzdxk'];
                        $data[$k]['bsd'] = $v['bsd'];
                        $data[$k]['rank'] = $v['rank'];
                        break;
                    }else{
                        $data[$k]['imgname'] = 0;
                        $data[$k]['id'] = $v['id'];
                        $data[$k]['province'] = $v['province'];
                        $data[$k]['logo'] = $v['logo'];
                        $data[$k]['dxmc'] = $v['dxmc'];
                        $data[$k]['zgbm'] = $v['zgbm'];
                        $data[$k]['is985'] = $v['is985'];
                        $data[$k]['is211'] = $v['is211'];
                        $data[$k]['iszizhu'] = $v['iszizhu'];
                        $data[$k]['ssd'] = $v['ssd'];
                        $data[$k]['gjzdxk'] = $v['gjzdxk'];
                        $data[$k]['bsd'] = $v['bsd'];
                        $data[$k]['rank'] = $v['rank'];
                    }
                }
            }
        }else{
            foreach($datas as $k=>$v){
                $data[$k]['imgname'] = 0;
                $data[$k]['id'] = $v['id'];
                $data[$k]['province'] = $v['province'];
                $data[$k]['logo'] = $v['logo'];
                $data[$k]['dxmc'] = $v['dxmc'];
                $data[$k]['zgbm'] = $v['zgbm'];
                $data[$k]['is985'] = $v['is985'];
                $data[$k]['is211'] = $v['is211'];
                $data[$k]['iszizhu'] = $v['iszizhu'];
                $data[$k]['ssd'] = $v['ssd'];
                $data[$k]['gjzdxk'] = $v['gjzdxk'];
                $data[$k]['bsd'] = $v['bsd'];
                $data[$k]['rank'] = $v['rank'];
            }
        }

        $data=array(
        'province'=>$province,
        'yxlx'=>$yxlx,
        'xlcc'=>$xlcc,
        'yxts'=>$yxts,
        'lists'=>$data
        );
        $this->apiReturn(100,'读取成功',$data);
    }
    /*院校检索*/
    public function search(){
        $studentid=$_POST['studentid'];

        $province=$_POST['province'];
        $yxlx=$_POST['yxlx'];
        $xlcc=$_POST['xlcc'];
        $yxts=$_POST['yxts'];
        if($province){
            $location['province'] = $province;
        }
        if($yxlx){
            $location['yxlx'] = $yxlx;
        }
        if($xlcc){
            $location['xlcc'] = $xlcc;
        }
        if($yxts == '985工程'){
            $location['is985'] = '985';
        }
        if($yxts == '211工程'){
            $location['is211'] = '211';
        }
        if($yxts == '教育部直属'){
            $location['zgbm'] = '教育部';
        }
        //院校列表
        $sql=M('d_university');//大学表
        $sqls = M('collect');  //收藏表
        $datas = $sql->where($location)->order('rank asc')->select();

        $where['studentid'] = $studentid;
        $where['schoolid'] = array('neq','');
        $info = $sqls->where($where)->select();

        if($info){
            foreach($datas as $k=>$v){
                foreach($info as $ki=>$vi){
                    if($v['id'] == $vi['schoolid']){
                        $data[$k]['imgname'] = 1;
                        $data[$k]['id'] = $v['id'];
                        $data[$k]['province'] = $v['province'];
                        $data[$k]['logo'] = $v['logo'];
                        $data[$k]['dxmc'] = $v['dxmc'];
                        $data[$k]['zgbm'] = $v['zgbm'];
                        $data[$k]['is985'] = $v['is985'];
                        $data[$k]['is211'] = $v['is211'];
                        $data[$k]['iszizhu'] = $v['iszizhu'];
                        $data[$k]['ssd'] = $v['ssd'];
                        $data[$k]['gjzdxk'] = $v['gjzdxk'];
                        $data[$k]['bsd'] = $v['bsd'];
                        $data[$k]['rank'] = $v['rank'];
                        break;
                    }else{
                        $data[$k]['imgname'] = 0;
                        $data[$k]['id'] = $v['id'];
                        $data[$k]['province'] = $v['province'];
                        $data[$k]['logo'] = $v['logo'];
                        $data[$k]['dxmc'] = $v['dxmc'];
                        $data[$k]['zgbm'] = $v['zgbm'];
                        $data[$k]['is985'] = $v['is985'];
                        $data[$k]['is211'] = $v['is211'];
                        $data[$k]['iszizhu'] = $v['iszizhu'];
                        $data[$k]['ssd'] = $v['ssd'];
                        $data[$k]['gjzdxk'] = $v['gjzdxk'];
                        $data[$k]['bsd'] = $v['bsd'];
                        $data[$k]['rank'] = $v['rank'];
                    }
                }
            }
        }else{
            foreach($datas as $k=>$v){
                $data[$k]['imgname'] = 0;
                $data[$k]['id'] = $v['id'];
                $data[$k]['province'] = $v['province'];
                $data[$k]['logo'] = $v['logo'];
                $data[$k]['dxmc'] = $v['dxmc'];
                $data[$k]['zgbm'] = $v['zgbm'];
                $data[$k]['is985'] = $v['is985'];
                $data[$k]['is211'] = $v['is211'];
                $data[$k]['iszizhu'] = $v['iszizhu'];
                $data[$k]['ssd'] = $v['ssd'];
                $data[$k]['gjzdxk'] = $v['gjzdxk'];
                $data[$k]['bsd'] = $v['bsd'];
                $data[$k]['rank'] = $v['rank'];
            }
        }
        if(!$data){
            $data=array();
        }
        $data=$data;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*收藏院校*/
    public function collect(){
        $studentid=$_POST['studentid'];
        $schoolid=$_POST['schoolid'];

        $where['schoolid']=$schoolid;
        $where['studentid']=$studentid;
        $info=M('collect')->where($where)->find();
        if($info){//已收藏，去掉
            M('collect')->where($where)->delete();

            $data=array(
            'imgname'=>'0',
            );
            $this->apiReturn(100,'请求成功',$data);
        }else{//未收藏，添加
            $where['time'] = date("Y-m-d H:i:s",time());
            $info = M('collect')->add($where);

            $data=array(
            'imgname'=>'1',
            );
            $this->apiReturn(100,'请求成功',$data);
        }
    }
    /*院校详情*/
    public function infos(){
        $id = $_POST['name'];
        
        $where['dxmc'] = $id;
        $info =M('d_university')->where($where)->find();
        $number['number'] = $info['number']+1;
        M('d_university')->where($where)->save($number);
        
        $jyb['univ_name']=$id;
        $xkpm=M('d_major_rank_jyb')->where($jyb)->order('rank asc')->select();

        foreach ($xkpm as $k => $v) {
            $where['Name']=$v['major'];
            $true=M('d_major')->where($where)->find();
            if($true){
                $xkpm[$k]['true']='1';
            }else{
                $xkpm[$k]['true']='0';
            }
        }
        $this->assign('info',$info);
        $this->assign('xkpm',$xkpm);

        $data=array(
            'info'=>$info,
            'xkpm'=>$xkpm
        );
        $this->apiReturn(100,'读取成功',$data);
    }
}