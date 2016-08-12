<?php
namespace Home\Controller;

use Think\Controller;

header("content-type:text/html;charset=utf-8");


class WeiPayController extends Controller
{
    //初始化
    public function _initialize()
    {
        //引入WxPayPubHelper
        vendor('WxPayPubHelper.WxPayPubHelper');

    }

    public function todoPost()
    {
        //使用native通知接口
        $nativeCall = new \NativeCall_pub();

        //接收微信请求
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        \Think\Log::write("【接收到的native通知】:\n" . $xml, \Think\Log::INFO);
        $nativeCall->saveData($xml);

        $orderInfo = array();
        $orderInfo['appid'] = $nativeCall->data['appid'];
        $orderInfo['openid'] = $nativeCall->data['openid'];
        $orderInfo['mch_id'] = $nativeCall->data['mch_id'];
        $orderInfo['is_subscribe'] = $nativeCall->data['is_subscribe'];
        $prodInfo = explode('-', $nativeCall->data['product_id']);
        $orderInfo['product_id'] = $prodInfo[0];
        $orderInfo['tel_number'] = $prodInfo[1];
        $payConfig = F($nativeCall->data['appid']);
        $wxPayConfig = new \WxPayConf($payConfig['app_id'], $payConfig['mch_id'], $payConfig['mch_key'], $payConfig['app_secret']);
        $nativeCall->wxPayConfig = $wxPayConfig;
        if ($nativeCall->checkSign() == FALSE) {
            $nativeCall->setReturnParameter("return_code", "FAIL");//返回状态码
            $nativeCall->setReturnParameter("return_msg", "签名失败");//返回信息
            \Think\Log::write("【接收到的native通知】:\n" . "签名失败", \Think\Log::ERROR);
        } else {
            //提取product_id
            $product_id =  explode('-', $nativeCall->getProductId())[0];
            //使用统一支付接口
            $unifiedOrder = new \UnifiedOrder_pub($wxPayConfig);
            //根据不同的$product_id设定对应的下单参数，此处只举例一种
            $courseInfo = F($product_id);
            if ($courseInfo != null) {
                //设置统一支付接口参数
                //设置必填参数
                $unifiedOrder->setParameter("body", $courseInfo['display_name']);//商品描述
                //自定义订单号，此处仅作举例
                $timeStamp = time();
                $out_trade_no = C('WxPay.pub.config.APPID') . "$timeStamp";
                $unifiedOrder->setParameter("out_trade_no", "$out_trade_no");//商户订单号
                $unifiedOrder->setParameter("total_fee", $courseInfo['total_price'] * 100);//总金额
//                $unifiedOrder->setParameter("total_fee", 1);//总金额
                $orderInfo['total_fee'] = $courseInfo['total_price'] * 100;

                $unifiedOrder->setParameter("notify_url", C('PAY_DOMAIN') . '/klxuexi/index.php/home/WeiPay/notify');//通知地址
                $unifiedOrder->setParameter("trade_type", "NATIVE");//交易类型
                $unifiedOrder->setParameter("product_id", $product_id);//用户标识
                $unifiedOrder->setParameter("spbill_create_ip", C('PAY_CREATE_IP'));//用户标识
                $orderInfo['out_trade_no'] = "$out_trade_no";
                //获取prepay_id
                $prepay_id = $unifiedOrder->getPrepayId();

                //设置返回码
                $nativeCall->setReturnParameter("return_code", "SUCCESS");//返回状态码
                $nativeCall->setReturnParameter("result_code", "SUCCESS");//业务结果
                $nativeCall->setReturnParameter("prepay_id", $prepay_id);//预支付ID
            } else {
                //设置返回码
                $nativeCall->setReturnParameter("return_code", "SUCCESS");//返回状态码
                $nativeCall->setReturnParameter("result_code", "FAIL");//业务结果
                $nativeCall->setReturnParameter("err_code_des", "此商品无效");//业务结果
            }
        }
        //将结果返回微信
        $returnXml = $nativeCall->returnXml();
        \Think\Log::write("【返回微信的native响应】:\n" . $returnXml . "\n", \Think\Log::INFO);
        echo $returnXml;
        \Think\Log::write("【orderInfo】:\n" . $orderInfo['total_fee'] . "\n", \Think\Log::INFO);
        $payOrder = M('payOrder');
        $payOrder->data($orderInfo)->add();
    }

    //异步通知
    public function notify()
    {
        if (!IS_POST) {
            echo '必须是POST请求';
            return;
        }
        //使用通用通知接口
        $notify = new \Notify_pub();
        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        \Think\Log::write("【收到的支付结果通知】:\n" . $xml . "\n", \Think\Log::INFO);
        $notify->saveData($xml);

        $payConfig = F($notify->data['appid']);
        $wxPayConfig = new \WxPayConf($payConfig['app_id'], $payConfig['mch_id'], $payConfig['mch_key'], $payConfig['app_secret']);
        $notify->wxPayConfig = $wxPayConfig;

        //验证签名，并回应微信。
        if ($notify->checkSign() == FALSE) {
            $notify->setReturnParameter("return_code", "FAIL");//返回状态码
            $notify->setReturnParameter("return_msg", "签名失败");//返回信息
        } else {
            $notify->setReturnParameter("return_code", "SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnXml();
        \Think\Log::write("【收到的支付结果返回xml】:\n" . $returnXml, \Think\Log::INFO);
        echo $returnXml;

        //==商户根据实际情况设置相应的处理流程，此处仅作举例=======
        if ($notify->checkSign() == TRUE) {
            if ($notify->data["return_code"] == "FAIL") {
                \Think\Log::write("【通信出错】:\n" . $xml . "\n", \Think\Log::INFO);
            } elseif ($notify->data["result_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                \Think\Log::write("【业务出错】:\n" . $xml . "\n", \Think\Log::INFO);
            } else {
                //此处应该更新一下订单状态，商户自行增删操作
                \Think\Log::write("【支付成功】:\n" . $xml . "\n", \Think\Log::INFO);
                $payNotify = M('wxPayNotify');
                $payNotify->data($notify->data)->add();
                $payOrder = M('payOrder');
                $updateData = array('is_success' => 'Y');
                $payOrder->where(array('openid' => $notify->data['openid'], 'out_trade_no' => $notify->data['out_trade_no']))->setField($updateData);
            }
        }
    }

}