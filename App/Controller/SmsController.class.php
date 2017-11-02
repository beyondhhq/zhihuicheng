<?php
namespace App\Controller;
use Think\Controller;
class SmsController extends Controller {


    public function send(){
        $demo = new \Think\Sms();
        //$mobile = 18510147949;
        //$mobile = 17710360903;
        $mobile=$_POST['shoujihao'];
        $type=$_POST['type'];
        $leixing=$_POST['leixing'];

        if(!$mobile){
           $data='请输入手机号!';
           $this->apiReturn(0,'操作失败',$data); 
        }
        if(!$type){
           $data='请填写验证码类型!';
           $this->apiReturn(0,'操作失败',$data); 
        }

        if($leixing){
           if($leixing=='student'){
             $where['Tel']=$mobile;
           }else{
             $where['phone']=$mobile;
           }
           $res=M($leixing)->where($where)->find();
           if($res){     
             if($type==5){
               $data="此手机号已注册绑定";
               $this->apiReturn(0,'操作失败',$data);
             }else{
               $response = $demo->sendSmsforapp($type,$mobile,4);
             }         
           }else{
             if($type==5){
               $response = $demo->sendSmsforapp($type,$mobile,4);
             }
             if($type==6){
               $data="此手机未绑定不能修改密码";
               $this->apiReturn(0,'操作失败',$data);
             }if($type==7){
               $data="此手机未绑定不能修改手机号";
               $this->apiReturn(0,'操作失败',$data);
             }
           
           }
        }else{        
            $data="请填写模块类型";
            $this->apiReturn(0,'操作失败',$data);
        }
        if($response->Code == 'OK'){
           $data="发送成功";
           $this->apiReturn(100,'操作成功',$data);
        }elseif($response->Code =='isp.RAM_PERMISSION_DENY'){
           $data="RAM权限DENY";
           $this->apiReturn(0,'操作失败',$data);
        }
        elseif($response->Code =='isv.OUT_OF_SERVICE'){
           $data="业务停机";
           $this->apiReturn(0,'操作失败',$data);            
        }
        elseif($response->Code =='isv.PRODUCT_UN_SUBSCRIPT'){

           $data="未开通云通信产品的阿里云客户";
           $this->apiReturn(0,'操作失败',$data);
        }
        elseif($response->Code =='isv.PRODUCT_UNSUBSCRIBE'){

           $data="产品未开通";
           $this->apiReturn(0,'操作失败',$data);
        }
        elseif($response->Code =='isv.ACCOUNT_NOT_EXISTS'){
           
           $data="账户不存在";
           $this->apiReturn(0,'操作失败',$data);
        }
        elseif($response->Code =='isv.ACCOUNT_ABNORMAL'){

            $data="账户异常";
            $this->apiReturn(0,'操作失败',$data);
        }
        elseif($response->Code =='isv.SMS_TEMPLATE_ILLEGAL'){

           $data="短信模板不合法";
           $this->apiReturn(0,'操作失败',$data); 
        }elseif($response->Code =='isv.SMS_SIGNATURE_ILLEGAL'){

           $data="短信签名不合法"; 
           $this->apiReturn(0,'操作失败',$data);
        }elseif($response->Code =='isv.INVALID_PARAMETERS'){

           $data="参数异常"; 
           $this->apiReturn(0,'操作失败',$data);
        }
        elseif($response->Code =='isp.SYSTEM_ERROR'){

           $data="系统错误"; 
           $this->apiReturn(0,'操作失败',$data);
        }
        elseif($response->Code =='isv.MOBILE_NUMBER_ILLEGAL'){

           $data="非法手机号"; 
           $this->apiReturn(0,'操作失败',$data);
        }
        elseif($response->Code =='isv.MOBILE_COUNT_OVER_LIMIT'){

           $data="手机号码数量超过限制";
           $this->apiReturn(0,'操作失败',$data);
        }
        elseif($response->Code =='isv.TEMPLATE_MISSING_PARAMETERS'){

           $data="模板缺少变量";
           $this->apiReturn(0,'操作失败',$data);
        }
        elseif($response->Code =='isv.BUSINESS_LIMIT_CONTROL'){

           $data="业务限流"; 
           $this->apiReturn(0,'操作失败',$data);
        }
        elseif($response->Code =='isv.INVALID_JSON_PARAM'){

           $data="JSON参数不合法，只接受字符串值"; 
           $this->apiReturn(0,'操作失败',$data);
        }
        elseif($response->Code =='isv.BLACK_KEY_CONTROL_LIMIT'){

           $data="黑名单管控"; 
           $this->apiReturn(0,'操作失败',$data);
        }
        elseif($response->Code =='isv.PARAM_LENGTH_LIMIT'){

           $data="参数超出长度限制";
           $this->apiReturn(0,'操作失败',$data); 
        }
        elseif($response->Code =='isv.PARAM_NOT_SUPPORT_URL'){

           $data="不支持URL"; 
           $this->apiReturn(0,'操作失败',$data);
        }
        elseif($response->Code =='isv.AMOUNT_NOT_ENOUGH'){

           $data="账户余额不足";
           $this->apiReturn(0,'操作失败',$data); 
        }

    }
    public function checkhao(){

        $mobile=$_POST['shoujihao'];
        $code=$_POST['yanzhengma'];
        $value=session($mobile);
        //print_R($_SESSION);exit;
        if($value==$code){
          $data="验证通过";
          $this->apiReturn(100,'操作成功',$data);


        }else{
          $data="验证码错误,请重新输入!";
          $this->apiReturn(0,'操作失败',$data);

        }

    }

    
}