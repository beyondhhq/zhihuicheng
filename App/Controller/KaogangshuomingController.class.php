<?php
namespace App\Controller;
use Think\Controller;
class KaogangshuomingController extends DomainController {
	public function getkaogang()
    {
        $where['course'] = $_POST['xueke'];
        //dump($_POST['xueke']);
        $where['class'] = '1';
        $where['kind'] = '4';
        $sql = M('teacher_kaogang');
        $data = $sql->where($where)->field('name,time,url')->find();
        if ($data) {
            $this->apiReturn(100,'提交成功',$data);
        }else{
            $this->apiReturn(0,'提交成功',"没有该科目");
        }
        
    }

    public function getkgsm()
    {
        $where['course'] = $_POST['xueke'];
        //dump($_POST['xueke']);
        $where['class'] = '2';
        $where['kind'] = '4';
        $sql = M('teacher_kaogang');
        $data = $sql->where($where)->field('name,time,url')->find();
        if ($data) {
            $this->apiReturn(100,'提交成功',$data);
        }else{
            $this->apiReturn(0,'提交成功',"没有该科目");
        }
    }

    /* 浙江默认显示 */
    public function getdefaulzj()
    {
        // 科目列表
        $kemu = M('student_gkdg')->field('name')->group('name')->select();
        $k = array();
        $i=0;
        foreach ($kemu as $key => $value) {
            $k[$i] = $value["name"];
            $i++;
        }
        // 默认显示的考试说明
        $where['name'] = $k[0];
        $list = M('student_gkdg')->field('url')->where($where)->select();

        $data["kemu"] = $k;
        $data["list"] = $list;
        $this->apiReturn(100,'提交成功',$data);
    }
    /* 浙江科目筛选 */
    public function filterxueke()
    {
        $name = $_POST['kemu'];
        $where['name'] = $name;
        $data = M('student_gkdg')->where($where)->select();
        $this->apiReturn(100,'提交成功',$data);
    }

}