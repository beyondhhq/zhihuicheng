<?php
namespace App\Controller;
use Think\Controller;
class DomainController extends Controller {
    protected function _initialize(){
        header("Access-Control-Allow-Origin: *");//跨域访问
    }
}