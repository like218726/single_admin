<?php
/**
 * Created by PhpStorm.
 * User: hutuo
 * Date: 2018/11/02
 * Time: 09:21
 */

namespace app\api\controller;

use think\Cache;
use think\Controller;
use think\Cookie;
use think\Request;

class Base extends Controller {

    protected $_weburl = '';
    protected $_uid = 1;
    protected $_shop_id = 1;
    protected $_shop_info = [];

    public function _initialize(){
        parent::_initialize();

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS');
        header("Access-Control-Allow-Headers:x-foo, x-requested-with, Content-Type, MUserAgent, MToken, UID,mid,token");


        $header = Request::instance()->header();

        if(empty($header['uid']))
        {
            ajaxError('请登录', -200)->send();
            exit;
        }else{
            $this->_uid = $header['uid'];
        }

        $info = model('ShopUser')->alias('a')->join('tv_shop b','a.shop_id = b.shop_id','left')->where(array('id'=>$this->_uid))->field('a.*,b.status as shop_status')->find();
        if (empty($info)){
            ajaxError('用户不存在', -901)->send();
            exit;
        }
        if ($info['shop_status'] != 1){
            ajaxError('门店已被禁用', -902)->send();
            exit;
        }
        if ($info['status'] != 1){
            ajaxError('账号已被禁用', -903)->send();
            exit;
        }
        $this->_shop_id = $info['shop_id'];
        $this->_shop_info = $info;

        //校验token合法性
        if(empty($header['token']))
        {
            ajaxError('TOKEN信息不存在', -904)->send();
            exit;
        }

        $accessToken = Cache::get("TOKEN_".$this->_uid);

        if(empty($accessToken))
        {
            ajaxError('请获取TOKEN信息', -905)->send();
            exit;
        }

        if($accessToken != $header['token'])
        {
            ajaxError('TOKEN失效，请重新获取：服务端token:'.$accessToken. " header Token:".$header['token'], -906)->send();
            exit;
        }
        $this->_weburl = "https://" . $_SERVER['HTTP_HOST'];
    }

    /**
     * 获取文件路径
     * @param $path文件路径
     */
    public function getFile($path){

        return $path ? $this->_weburl . $path : '';
    }
}