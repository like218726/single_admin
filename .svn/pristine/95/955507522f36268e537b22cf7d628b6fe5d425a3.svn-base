<?php
/**
 * Created by PhpStorm.
 * User: lijunhua
 * Date: 2019/3/11
 * Time: 17:52
 */

namespace app\api\controller;


use app\common\HttpCurl;
use app\common\WechatAppPay;
use think\Controller;
use think\Cookie;
use think\Request;

class Login extends Controller
{
    /**
     *
     * 发送验证码
     */
    public function SendSms() {
        $mobile = trim(input('post.mobile/s'));
        $code = rand(100000, 999999);

        if (empty($mobile)) {
            return ajaxError("手机号不能为空");
        }
        if(!preg_match("/^1[3-9]{1}\d{9}$/",$mobile)){
            return ajaxError("请正确填写手机号码");
        }

        //判断是否存在验证码
        $data = model("VerifySms")->where(array('mobile'=>$mobile, 'status'=>1))->order('vs_id DESC')->find();
        //获取时间配置
        $alicloud = config('ALICLOUD');
        $sms_time_out = $alicloud['sms_time_out'];
        $sms_time_out = $sms_time_out ? $sms_time_out : 60;
        //60秒以内不可重复发送
        $times = intval(time()) - intval($data['addtime']);
        if($data && ($times < $sms_time_out)){
            return ajaxError($sms_time_out.'秒内不允许重复发送');
        }

        $ip = get_client_ip();
        $count = model("VerifySms")->where(array('ip'=>$ip, 'addtime'=>array('gt',strtotime(date('Y-m-d')))))->count();
        if($count >= 10)
        {
            return ajaxError('您今天发送验证码的数量已超过限额！');
        }

        $sms_template_code = 'SMS_160577197';
        $send = send_sms_reg($mobile, $code,$sms_template_code);
        list($status,$return_code,$sub_code) = $send;

        if($status){
            return ajaxSuccess('发送成功');
        }else if(!$status && $sub_code=='isv.BUSINESS_LIMIT_CONTROL'){
            model("VerifySms")->insert(['mobile'=>$mobile,'code'=>$code,'status'=>2,'addtime'=>time(), 'ip'=>$ip,'memo'=>'发送失败,'.$sub_code]);
            return ajaxError('您今天发送验证码的数量已超过限额！');
        }else{
            model("VerifySms")->insert(['mobile'=>$mobile,'code'=>$code,'status'=>2,'addtime'=>time(), 'ip'=>$ip,'memo'=>'发送失败,'.$sub_code]);
            return ajaxError('发送失败,'.$sub_code);
        }
    }

    /**
     * 检测登录
     * @access public
     * @return void
     */
    public function check(){
        $username     = input('post.username/s');             //获取手机号
        $password   = input('post.password/s');           //获取输入密码

        if(empty($username)){
            return ajaxError('请正确输入用户名');
        }
        if(empty($password)){
            return ajaxError('请输入密码');
        }


        $info = model('ShopUser')->alias('a')->join('tv_shop b','a.shop_id = b.shop_id','left')->where(array('username'=>$username))->field('a.*,b.status as shop_status')->find();

        if (empty($info)){
            return ajaxError('用户不存在');
        }
        if ($info['status'] != 1){
            return ajaxError('账号已被禁用');
        }
        if ($info['shop_status'] != 1){
            return ajaxError('门店已被禁用');
        }

        if($info["password"] != user_md5($password) ){
            return ajaxError('用户名和密码不匹配');
        }

        $uid = $info['id'];

        //更新用户数据
        $request = Request::instance();
        $ip = $request->ip();

        model('ShopUser')->save(['last_login'=>time(),'last_ip'=>$ip,'login_count'=>$info['login_count']+1],['id'=>$uid]);

        return ajaxSuccess('',1,["uid"=>$uid]);
    }

    /**
     * 获取openid
     * @access public
     * @return void
     */
    public function getOpenid(){
        $code = input('post.code/s');

        if (empty($code)){
            return json(['code'=>0,'msg'=>'参数错误']);
        }

        $wechat = new WechatAppPay();
        $res = $wechat -> getOpendId($code);
        if(!empty($res['errcode'])){
            return ajaxError($res['errmsg']);
        }

        $openid = $res['openid'];

        return ajaxSuccess('',1,['openid'=>$openid]);
    }
    /**
     * 验证--验证码
     */
    private function checkVf($mobile,$vf){
        $option = [
            "mobile"  => $mobile,
            "status"  => 1,
            "addtime" => ['egt',time()-2*3600]
        ];
        $re = model('VerifySms')->where($option)->order('vs_id DESC')->find();

        if ($vf == $re['code']){
            //验证成功后修改验证码状态
            model('VerifySms')->save(["status"=>3,"upttime"=>time()],["vs_id"=>$re['vs_id']]);
            return true;
        }
        return false;
    }
}