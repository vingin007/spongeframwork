<?php
include_once APP_PATH.'module/notify/library/driver/notify_abstract.class.php';
include_once APP_PATH.'module/notify/library/driver/sms/alidayu/ali_autoloader.php';
class sms extends notify_abstract{
	public function __construct($_config) {
		$this->config = $_config;
		$this->sms = json_decode($this->config['params'],TRUE);
	}

	public function send(){
		$client = new TopClient;
		$client->appkey = $this->config['configs']['appkey'] ;
		$client->secretKey = $this->config['configs']['secret'];
		$request = new AlibabaAliqinFcSmsNumSendRequest;
		$request->setSmsType("normal");
		$request->setSmsFreeSignName($this->config['configs']['sms_sign']);
		$request->setSmsParam(json_encode($this->sms['tpl_vars']));
		$request->setRecNum($this->sms['mobile']);
		$request->setSmsTemplateCode($this->sms['tpl_id']);
		$result = $client->execute($request);
		var_dump($result);die;
		$send = ($result['code'] == 200 && $result['result'] == TRUE) ? TRUE : FALSE;
		return $this->_notify($send);
	}
}
