<?php
$config = array(
    //在线支付验证域名
    'PAY_DOMAIN' => 'http://www.klxuexi.com',
    //在线支付IP
    'PAY_CREATE_IP' => '114.55.72.87',
    //发送短信验证码的URL
    'SEND_VERIFYCODE_URL' => 'http://120.55.97.140/shortmessage/message/sendVerifyCodeMessage?',
    'SEND_CRM_URL' => "120.55.97.140:8084/gxhCRM/api/addResc?",
    //接收验证码的短信内容模板
    'GET_VCODE_MSG_CONTENT' => "您的验证码是#verify_code#。如非本人操作，请忽略本短信",
    //预约成功提示短信
    'APPOINT_SUCCESS_MSG_COUTENT' => "尊敬的#name#，您的预约我们已经受理，感谢您的预约咨询，我们会安排工作人员与您沟通，请保持电话畅通。",
    'BASECODE_KEY' => "klxx123",
    // 显示页面Trace信息
    'SHOW_PAGE_TRACE' => true,
    'URL_CASE_INSENSITIVE' => true,
    'SITE_VERSION' => '20160704',
);

return array_merge(include './Conf/config.php', $config);