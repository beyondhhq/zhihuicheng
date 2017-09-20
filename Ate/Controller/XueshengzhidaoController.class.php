<?php
namespace Ate\Controller;
use Think\Controller;
class XueshengzhidaoController extends DomainController {
    /*教师角色 班主任*/
    public function banzhuren(){
		$sql = M('teacher_xsczzd');
      	$where['kind'] = 1;
		$where['class'] = 5;
        $data =$sql->where($where)->field('id,name,class,kind,time,pict,fname,student_name')->order('time desc')->select();
        $this->apiReturn(100,'读取成功',$data);
    }

    /*教师角色 任课教师*/
    public function renkejiaoshi(){
		$sql = M('teacher_zyfz');
		$wheres['kind']=1;
      	$wheres['class']=2;
        $data =$sql->where($wheres)->field('id,name,class,kind,time,pict,fname,student_name')->order('time desc')->select();
        $this->apiReturn(100,'读取成功',$data);
    }

	/*教师角色 一生一导*/
    public function yishengyidao(){
		$sql = M('teacher_zyfz');
		$wheres['kind']=1; //经验交流或者论文专著文章id
		$wheres['class']=3; //经验交流或者论文专著文章id
        $data =$sql->where($wheres)->field('id,name,class,kind,time,pict,fname,student_name')->order('time desc')->select();
        $this->apiReturn(100,'读取成功',$data);
    }

    /*教师角色 班主任 详情*/
    public function bzrdetail(){
        $id = $_POST['id'];
        $where['id'] = $id;
        $data = M('teacher_xsczzd')->field('id,name,content,fname,student_name')->where($where)->find();
        $this->apiReturn(100,'读取成功',$data);
    }

    /*教师角色 任课教师 详情*/
    public function rkjsdetail(){
        $id = $_POST['id'];
        $where['id'] = $id;
        $data = M('teacher_zyfz')->field('id,name,content,fname,student_name')->where($where)->find();
        $this->apiReturn(100,'读取成功',$data);
    }

    /*教师角色 一生一导 详情*/
    public function ysyddetail(){
        $id = $_POST['id'];
        $where['id'] = $id;
        $data = M('teacher_zyfz')->field('id,name,content,fname,student_name')->where($where)->find();
        $this->apiReturn(100,'读取成功',$data);
    }

    /*学业规划 选科指导*/
    public function xuankelist(){
    	$where['kind'] = '1';
		$where['class'] = '1';
		$Data = M('teacher_xsczzd'); // 实例化Data数据对象  date 是你的表名
		$list = $Data->where($where)->field('id,name,class,kind,time,pict,fname,student_name')->order('time desc')->select();

		$sql = M('video');
		$wheres['class'] = 3;
		$wheres['kind'] = 1;
		$two = $sql->where($wheres)->field('ViideoID,VideoName,image,time, VideocSrc')->select();

		$data["list"] = $list;
		$data["videos"] = $two;
		$this->apiReturn(100,'读取成功',$data);
    }

	/*学业规划 选科指导 详情*/
    public function xkdetail(){
        $id = $_POST['id'];
        $where['id'] = $id;
        $data = M('teacher_xsczzd')->field('id,name,content,fname,student_name')->where($where)->find();
        $this->apiReturn(100,'读取成功',$data);
    }

    /*学业规划 备考指导 列表*/
    public function bklist(){
        $sql = M('teacher_xsczzd');
		$whereo['kind'] = '1';
		$whereo['class'] = '6';
		$one = $sql->where($whereo)->field('id,name,class,kind,time,pict')->order('id desc')->limit(4)->select();
		
		$wheret['kind'] = '1';
		$wheret['class'] = '7';
		$two = $sql->where($wheret)->field('id,name,class,kind,time,pict')->order('id desc')->limit(4)->select();
		
		$whereh['kind'] = '1';
		$whereh['class'] = '8';
		$three = $sql->where($whereh)->field('id,name,class,kind,time,pict')->order('id desc')->limit(8)->select();

		$data["one"] = $one;
		$data["two"] = $two;
		$data["three"] = $three;
		$this->apiReturn(100,'读取成功',$data);
    }

    /*学业规划 备考指导 详情*/
    public function bkdetail(){
        $id = $_POST['id'];
        $where['id'] = $id;
        $data = M('teacher_xsczzd')->field('id,name,content,fname,student_name')->where($where)->find();
        $this->apiReturn(100,'读取成功',$data);
    }

    /*学业规划 备考指导 专家讲座列表*/
    public function zjjzlist()
    {
    	$where['class'] = 2;
		$where['kind'] = 5;
		$info = M('video')->field('ViideoID,VideoName,image,time, VideocSrc')->where($where)->select();
		$this->apiReturn(100,'读取成功',$info);
    }

    /*学业规划 备考指导 百问百答*/
    public function getbwbd()
    {
		$where['class']=$_POST['id'];
		$info=M('parent_bwbd')->where($where)->select();
		$this->apiReturn(100,'读取成功',$info);
    }
	/*推荐 列表*/
    public function xszdhotlist()
    {
    	// type 1为teacher_zyfz表数据 2为teacher_xsczzd表数据
    	// 教师角色推荐列表
    	
        // 任课教师 一生一导3条
        $s = M('teacher_zyfz');
        $wheres['class'] = array('in',array('2','3'));
        $wheres['kind'] = '1';
        $other = $s->where($wheres)->field('id,name,class,kind,time,pict')
        ->order('time desc')->limit(3)->select();
        $new_other = array();
        foreach ($other as $key => $value) {
        	$value["type"] = 1;
        	$new_other[$key] = $value;
        }

    	// 学业规划推荐列表和班主任
    	$where['kind'] = '1';
		$where['class'] = array('in',array('1','5'));
		$Data = M('teacher_xsczzd');
		$list = $Data->where($where)->field('id,name,class,kind,time,pict')
		->order('time desc')->limit(5)->select();
		$new_list = array();
		foreach ($list as $key => $value) {
        	$value["type"] = 2;
        	$new_list[$key] = $value;
        }

		$data = array_merge($new_list, $new_other);
		$this->apiReturn(100,'读取成功',$data);
    }

    /*推荐 详情*/
    public function xszdhotdetail(){
        $id = $_POST['id'];
        $type = $_POST['type'];
        $where['id'] = $id;
        if ($type == 1) {
        	// teacher_zyfz表
        	$data = M('teacher_zyfz')->field('id,name,content')->where($where)->find();
        	$this->apiReturn(100,'读取成功',$data);
        }else{
			$data = M('teacher_xsczzd')->field('id,name,content')->where($where)->find();
			$this->apiReturn(100,'读取成功',$data);
        }
        
    }
    /*专家指导*/
    public function zhuanjiazhidao(){
        $wherevc['kind'] = '1';
        $wherevc['class'] = '4';
        $list =M('teacher_xsczzd')->where($wherevc)->order('time desc')->select();
        $data=$list;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*评价案例*/
    public function pingjiaanli(){
        $fanli['category']='记录范例';
        $fanlis = M('jilufanli')->where($fanli)->order('time desc')->select();
        $data=$fanlis;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*案例详情*/
    public function fanlis(){
        $id = $_POST['id'];
        $where['id'] = $id;
        $info = M('jilufanli')->where($where)->find();
        $this->apiReturn(100,'读取成功',$info);
    }
    /*指导详情*/
    public function zhidao_detail(){
        $id = $_POST['id'];
        $where['id'] = $id;
        $info = M('teacher_xsczzd')->where($where)->find();
        $this->apiReturn(100,'读取成功',$info);
    }
}