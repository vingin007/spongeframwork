<?php
require_cache(APP_PATH.config('DEFAULT_H_LAYER').'/api/library/api_abstract.class.php');
class attachment_api extends api_abstract{
	public function _initialize() {
		parent::_initialize();
		helper('attachment');
	}

	public function upload(){
		$site_url = model('admin/setting','service')->get('site_url');
		$data = array();
		$data['mid'] = $this->mid;
		$upload_init = attachment_init($data);
		$file = (isset($_GET['file'])) ? $_GET['file'] : 'upfile';
		$url = $this->load->service('attachment/attachment')->setConfig($upload_init)->upload($file);
		if(!$url){
			$this->error = $this->load->service('attachment/attachment')->error;
			return FALSE;
		}
		$url = str_replace('/uploadfile', $site_url.'/uploadfile', $url);
		return $url;
	}
}