<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 判断是否是系统管理员
 * @param mixed $uid
 * @return bool
 */
function isAdministrator( $uid = '' ){
    if( empty($uid) ) $uid = session('uid');
    if( is_array(config('USER_ADMINISTRATOR')) ){
        if( is_array( $uid ) ){
            $m = array_intersect( config('USER_ADMINISTRATOR'), $uid );
            if( count($m) ){
                return TRUE;
            }
        }else{
            if( in_array( $uid, config('USER_ADMINISTRATOR') ) ){
                return TRUE;
            }
        }
    }else{
        if( is_array( $uid ) ){
            if( in_array(config('USER_ADMINISTRATOR'),$uid) ){
                return TRUE;
            }
        }else{
            if( $uid == config('USER_ADMINISTRATOR')){
                return TRUE;
            }
        }
    }
    return FALSE;
}

/**
 * 导出excel
 * @param $strTable	表格内容
 * @param $filename 文件名
 */
function downloadExcel($strTable,$filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=".$filename."_".date('Y-m-d').".xls");
    header('Expires:0');
    header('Pragma:public');
    echo '<html><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'.$strTable.'</html>';
}

/**
 * 
 * 手机号验证
 * @param unknown_type $phone
 */
function is_mobile ( $mobile ) {
	if (preg_match("/^1[3-9]{1}\d{9}$/",$mobile)) {
		return true;
	} else {
		return false;
	}
}

/**
 * 
 * 电话号码验证
 * @param unknown_type $phone
 */
function is_phone ( $phone ) {
	if (preg_match("/^([0-9]{3,4}-)?[0-9]{7,8}$/", $phone)) {
		return true;
	} else {
		return false;
	}
}

/**
 * 
 * url验证
 * @param unknown_type $url
 */
function is_url ( $url ) {
	if (preg_match("/^http(s)?:\\/\\/.+/", $url)) {
		return true;
	} else {
		return false;
	}	
}

/**
 * 把返回的数据集转换成Tree
 * @param $list
 * @param string $pk
 * @param string $pid
 * @param string $child
 * @param string $root
 * @return array
 */
function listToTree($list, $pk='id', $pid = 'fid', $child = '_child', $root = '0') {
    $tree = array();
    if(is_array($list)) {
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }
        foreach ($list as $key => $data) {
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] = &$list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent = &$refer[$parentId];
                    $parent[$child][] = &$list[$key];
                }
            }
        }
    }
    return $tree;
}


/**
 * 将对象转换成数组
 */
function object_to_array($e)
{
    $e = (array )$e;
    foreach ($e as $k => $v) {
        if (gettype($v) == 'resource')
            return;
        if (gettype($v) == 'object' || gettype($v) == 'array')
            $e[$k] = (array )object_to_array($v);
    }
    return $e;
}

/**
 * @Action    调试打印
 * @Param     $var      需要打印的值
 *            $method   需要打印的方式
 *            $exit     是否停止程序继续执行
 * @Return    void
 */
function xdebug($var, $exit = false, $method = true)
{
    echo '<meta content-type:"text/html" charset="utf-8" />';
	echo ' <pre>';
    $method ? print_r($var) : var_dump($var);
    echo '</pre> ' . '<hr style="color:red">' . '<br>';
    
	exit;
    
}

function formatTree($list, $lv = 0, $title = 'name'){
    $formatTree = array();
    foreach($list as $key => $val){
        $title_prefix = '';
        for( $i=0;$i<$lv;$i++ ){
            $title_prefix .= "|---";
        }
        $val['lv'] = $lv;
        $val['namePrefix'] = $lv == 0 ? '' : $title_prefix;
        $val['showName'] = $lv == 0 ? $val[$title] : $title_prefix.$val[$title];
        if(!array_key_exists('_child', $val)){
            array_push($formatTree, $val);
        }else{
            $child = $val['_child'];
            unset($val['_child']);
            array_push($formatTree, $val);
            $middle = formatTree($child, $lv+1, $title); //进行下一层递归
            $formatTree = array_merge($formatTree, $middle);
        }
    }
    return $formatTree;
}

if (!function_exists('array_column')) {
    function array_column($array, $val, $key = null){
        $newArr = array();
        if( is_null($key) ){
            foreach ($array as $index => $item) {
                $newArr[] = $item[$val];
            }
        }else{
            foreach ($array as $index => $item) {
                $newArr[$item[$key]] = $item[$val];
            }
        }
        return $newArr;
    }
}

/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @param  string $auth_key 要加密的字符串
 * @return string
 * @author jry <598821125@qq.com>
 */
function user_md5($str, $auth_key = ''){
    if(!$auth_key){
        $auth_key = config('AUTH_KEY');
    }
    return '' === $str ? '' : md5(sha1($str) . $auth_key);
}

function ajaxSuccess( $msg, $code = 1, $data = array() ){
    $returnData = array(
        'code' => $code,
        'msg' => $msg,
        'data' => $data
    );
    return json($returnData);
}

function ajaxError( $msg, $code = -1 ){
    $returnData = array(
        'code' => $code,
        'msg' => $msg
    );
    return json($returnData);
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip() {
    $ip = '';
    if (isset($_SERVER['HTTP_CDN_SRC_IP']) && $_SERVER['HTTP_CDN_SRC_IP']) {
        $ip = $_SERVER['HTTP_CDN_SRC_IP'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
        $allIps = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $allIpsArray = explode(',', $allIps);
        $ip = $allIpsArray[0];
    } else if (isset($_SERVER['HTTP_X_REAL_IP']) && $_SERVER['HTTP_X_REAL_IP']) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    if (empty($ip)) {
        $ip = '0.0.0.0';
    }
    return $ip;
}

/**
 * 发送验证码短信
 * @param string $mobile  手机号码
 * @param int $code    验证码
 * @param string $sms_template_code    模板编号
 * @return bool    短信发送成功返回true失败返回false
 *
 * 验证码模板：${product}用户注册验证码：${code}。请勿将验证码告知他人并确认该申请是您本人操作！
 */
function send_sms_reg( $mobile, $code,$sms_template_code)
{
    $alicloud = config('ALICLOUD');
    $sign_name = $alicloud['SignName']; //签名名称
    if(empty($sms_template_code) || empty($sign_name)){
        return false;
    }

    $sms_cfg = json_encode(array('code'=>$code));

    $db = model("VerifySms");
    $map = array(
        'mobile' => $mobile,
        'code' => $code,
        'status' => 1,
        'addtime' => array('gt', time() -  60),
    );
    $data = $db->where($map)->find();
    if (!empty($data)){
        return array(false,'','发送信息过于频繁');
    }

    // 发送验证码短信
    vendor('dysms.Sms');
    $sms = new Sms();

    $alicloud = config('ALICLOUD');
    $sms->appkey = $alicloud['AccessKeyId'];
    $sms->secretKey = $alicloud['AccessKeySecret'];

    $sms_send = $sms->sendSms($mobile, $sms_template_code, $sms_cfg, $sign_name);

    $success = $sms_send->Code == 'OK' ? true : false; //成功标识
    $return_code = $sms_send->Code; //返回的编码
    $sub_code = $sms_send->Message; //错误码

    if ($success)
    {
        $db->insert(array('mobile' => $mobile, 'code' => $code, 'addtime' => time(), 'ip'=>get_client_ip()));

        return array(true,'','');
    } else {
        return array(false,$return_code,$sub_code);
    }
}

