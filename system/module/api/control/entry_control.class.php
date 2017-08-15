<?php

class entry_control extends control {
	protected $_params = array();
	protected $_result = array();

	public function _initialize() {
		parent::_initialize();
		$this->_params = $_GET;
		$this->_result = array('code'=> -99999 ,'msg'=> lang('_api_exception_','api/language'),'result' =>  false);
	}

	public function _empty() {
		if (empty($this->_params['timestamp'])) {
			$this->_result['code']   = -99990;
			$this->_result['msg']    = lang('_timestamp_not_empty_','api/language');
			$this->_result['result'] = false;
			$this->_format();
			return FALSE;
		}
		list($server, $type, $module, $file, $function) = explode(".", $this->_params['method']);
		if (!$function) {
			$this->_result['code']   = -99998;
			$this->_result['msg']    = lang('_method_error_','api/language');
			$this->_result['result'] = false;
		} else {
			require_cache(APP_PATH.config('DEFAULT_H_LAYER').'/api/library/api_factory.class.php');
			$factory = new api_factory($module, $type, $file, $server);
			$this->_result['result'] = ($factory->get_code() == 200) ? $factory->$function() : '';
			$this->_result['code']   = $factory->get_code();
			$this->_result['msg']    = $factory->get_msg();
		}
		/* 根据请求返回数据 */
		$this->_format();
	}

	/* 根据系统级参数format返回相应格式数据 */
	private function _format() {
		if ($this->_params['format'] == 'xml') {
			header('Content-Type:text/xml; charset='.$this->_params['charset']);
			exit(array2xml($this->_result));
		} else {
			header('Content-type:application/json');
			exit(json_encode($this->_result));
		}
	}
}