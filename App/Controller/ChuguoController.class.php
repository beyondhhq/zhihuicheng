<?php
namespace App\Controller;
use Think\Controller;
class ChuguoController extends DomainController {
	/*出国留学 资讯*/
    public function zixun(){
        $sqls = M('recruit_advisory');
        $wherea['plate'] = 6;
        $wherea['kind'] = 1;
        $advisory = $sqls->where($wherea)->field('id,name,time')->order('time desc')->select();
        $data=$advisory;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*出国留学 方略*/
    public function fanglue(){
        $sqls = M('recruit_advisory');
        $wherea['plate'] = 6;
        $wherea['kind'] = 2;
        $advisory = $sqls->where($wherea)->field('id,name,time')->order('time desc')->select();
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
    /*外国高等学校名单*/
    public function schools(){
        $schools=array('美国','塞浦路斯','丹麦','英国','希腊','爱尔兰','荷兰','挪威','南非','新加坡','澳大利亚','德国','法国','芬兰','韩国','加拿大','日本','瑞典','瑞士','新西兰','意大利','波兰','乌克兰','俄罗斯','埃及','菲律宾','奥地利','泰国','保加利亚','比利时','西班牙','古巴','匈牙利','葡萄牙','罗马尼亚','喀麦隆','阿尔及利亚','白俄罗斯','毛里求斯','斯里兰卡','吉尔吉斯斯坦','拉脱维亚','列支敦士登','以色列','卢森堡','马耳他','捷克','牙买加','克罗地亚','哥斯达黎加');
        $data=$schools;
        $this->apiReturn(100,'读取成功',$data);
    }
    /*外国高等学校 详情*/
    public function detail(){
        $where['country'] = $_POST['name'];
        $info = M('d__recruit_overseas')->where($where)->find();
        $data=$info;
        $this->apiReturn(100,'读取成功',$data);
    }
}