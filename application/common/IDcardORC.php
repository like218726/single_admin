<?php
/**
 * Created by PhpStorm.
 * User: lijunhua
 * Date: 2019/4/10
 * Time: 11:14
 */

namespace app\common;


class IDcardORC
{
    private $_secret_id     = 'AKIDoSinfPCLgs33oXG8vZ3JsELHLAoEZN7o';
    private $_secret_key    = 'f6PULxKXWLwJSUIsFOJ3XnnY3KL7duwi';
    private $_url           = 'ocr.tencentcloudapi.com/';
    private $_action        = 'IDCardOCR';
    private $_version       = '2018-11-19';
    private $_region       = 'ap-guangzhou';
    private $_image_url     = '';
    private $_card_side     = '';

    /**
     * 身份证识别
     * @param string $imageUrl 身份证图
     * @param string $side  身份证面（FRONT：正面，BACK：背面）
     */
    public function __construct($imageUrl,$side='FRONT')
    {
        $this->_image_url = $imageUrl;
        $this->_card_side = $side;
    }

    public function getCardInfo(){
        $nonce = rand(10000,99999);
        $data = [];
        $data['Action'] = $this->_action;
        $data['Version'] = $this->_version;
        $data['Region'] = $this->_region;
        $data['SecretId'] = $this->_secret_id;
        $data['Nonce']     = $nonce;
        $data['ImageUrl'] = $this->_image_url;
        $data['CardSide'] = $this->_card_side;
        $data['Timestamp'] = time();

        $str = $this->formatBizQueryParaMap($data,'POST');
        $sign = $this->getSign($str);
        $data['Signature'] = $sign;

        return $this->curlRequest('https://'.$this->_url,$data,'POST');
    }

    private function getSign($str){
        $secretKey = $this->_secret_key;
        $signStr = base64_encode(hash_hmac('sha1', $str, $secretKey, true));
        return $signStr;
    }

    /**
     * 	作用：格式化参数，签名过程需要使用
     */
    private function formatBizQueryParaMap($paraMap,$method, $urlencode=false)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v)
        {
            if($urlencode)
            {
                $v = urlencode($v);
            }
            //$buff .= strtolower($k) . "=" . $v . "&";
            $buff .= $k . "=" . $v . "&";
        }
        if (strlen($buff) > 0)
        {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $method.$this->_url.'?'.$reqPar;
    }

    /**
     * curl一般请求方法
     * @param  string $url 请求地址
     * @param array $data   请求数据
     * @param string $method    请求方式
     * @param string $data_type 请求的头部和数据类型
     * @return mixed|string
     */
    public static function curlRequest ( $url ,$data = [],$method = "GET",$data_type = "" )
    {
        if ( $data_type == "json" ){
            $request_data = json_encode( $data ,JSON_UNESCAPED_UNICODE );
            $header  = [
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: '.strlen($request_data)
            ];
        }else{
            $header = [];
            $request_data = http_build_query($data); //够着请求数据
        }

        $ch = curl_init();													// 启动一个CURL会话
        curl_setopt($ch, CURLOPT_URL, $url);							// 要访问的地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);						// 要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_HEADER, FALSE); 							// 显示返回的Header区域内容
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT5 .0)');	// 模拟用户使用的浏览器
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); 								// 设超时限置制防止死循环
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);			//设置请求方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_data);						//提交的数据包

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);					// 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);	// 从证书中检查SSL加密算法是否存在

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);	// 使用自动跳转
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // 自动设置Referer

        if ( $data_type != "" ){
            curl_setopt( $ch,CURLOPT_HTTPHEADER , $header);
        }


        $tmpInfo = curl_exec($ch);	// 执行操作
        if (curl_errno($ch)) {
            return 'Error：'.curl_error($ch);
        }
        curl_close($ch);// 关闭CURL会话
        return json_decode($tmpInfo,true); // 返回数据(json 格式)
    }
}