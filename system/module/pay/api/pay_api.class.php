<?php
require_cache(APP_PATH.config('DEFAULT_H_LAYER').'/api/library/api_abstract.class.php');
class pay_api extends api_abstract{
	public function __construct() {
		$this->service = $this->load->service('pay/payment');
		$this->order_service = $this->load->service('order/order');
		$this->setting_serivce = $this->load->service('admin/setting');
	}
	/*获取已开启支付方式*/
	public function payments(){
		$pays = $this->setting_serivce->get('pays');
		$result = $this->service->getpayments('wap', $pays);
		if(!$result){
			$this->code = -411101;
			$this->msg = lang('未找到数据');
			return false;
		}
		return $result;
	}
}