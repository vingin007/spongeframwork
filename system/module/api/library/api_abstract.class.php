<?php
abstract class api_abstract extends hd_base{

	protected $code = 200;

	protected $msg = '';

	protected $mid;

	protected $st;

	private $codeCh = array(
		200  => '_handle_success_',
	);
	public $params = array();

	public function _initialize() {
		if($this->check_token()===false){
			return false;
		};
        // if($this->valid_sign() === false) {
        //     return false;
        // }
        return true;
    }

	public function get_code() {
		return $this->code;
	}

	public function get_msg() {
		return empty($this->msg) ? lang($this->codeCh[$this->code]) : $this->msg;
	}
	// /**
	//  * 校验签名
	//  */
	// public function valid_sign(){
	// 	 if(empty($_GET['appid'])){
 //            $this->code = -90001;
 //            $this->msg = lang('_appid_not_empty_','api/language');
 //            return false;
 //        }
 //        $accounts = $this->load->table('api/developer')->cache('developer',3600)->select();
 //        if(!$accounts[$_GET['appid']] || $_GET['appid']  != authcode($accounts[$_GET['appid']]['secret'])){
	// 		$this->code = -90002;
 //            $this->msg = lang('_appid_not_exist_','api/language');
 //            return false;
 //        }
 //        if(!$accounts[$_GET['appid']]['status']){
 //        	$this->code = -90003;
 //            $this->msg = lang('_appid_not_access_','api/language');
 //            return false;
 //        }
 //        return true;
	// }

	public function check_token(){
		if(isset($_GET['token'])){
			list($this->mid, $identifier,$ts) = explode("\t", decrypt($_GET['token'],'ENCODE'));
			if((int)$this->mid < 1){
				$this->code = -90004;
				$this->msg = url('member/index/index');
				return false;
			}
			if(time()>$ts+10*86400){
				$this->code = -90004;
				$this->msg = url('member/index/index');
				return false;
			}
		}
		return ture;
	}
}