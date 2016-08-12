<?php


/**
 *    配置账号信息
 */
class WxPayConf
{
    private $appId;
    private $mchId;
    private $key;
    private $appSecret;

    private $curl_timeout = 30;

    // 构造函数
    function __construct($appId, $mchId, $key, $appSecret)
    {
        $this->appId = $appId;
        $this->mchId = $mchId;
        $this->key = $key;
        $this->appSecret = $appSecret;

    }

    private function __get($property_name)
    {
        if (isset($this->$property_name)) {
            return ($this->$property_name);
        } else {
            return (NULL);
        }
    }


//=======【基本信息设置】=====================================
//微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
    const APPID = 'wx37831d54ac552fbd';
//受理商ID，身份标识
    const MCHID = '1315241901';
//商户支付密钥Key。审核通过后，在微信发送的邮件中查看
    const KEY = 'klxuexifuzhouDxb2016022487654321';
//JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
    const APPSECRET = '2441f9c68d3703d37e6a640517476b6d';


//=======【证书路径设置】=====================================
//证书路径,注意应该填写绝对路径
    const SSLCERT_PATH = './cacert/apiclient_cert.pem';
    const SSLKEY_PATH = './cacert/apiclient_key.pem';

//=======【curl超时设置】===================================
//本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
    const CURL_TIMEOUT = 30;
}

?>