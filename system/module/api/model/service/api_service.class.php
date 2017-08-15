<?php
/**
 *		api数据层
 *      [Haidao] (C)2013-2099 Dmibox Science and technology co., LTD.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.haidao.la
 *      tel:400-600-2042
 */

class api_service extends service {
	public function _initialize() {}
	/**
	 * 添加开发者账号
	 */
	public function add(){
		$data = array();
		$data['appid'] = '2016'.substr(TIMESTAMP, -4,4).random(6,1);
		$data['secret'] = authcode($data['appid'],'ENCODE');
		$data['status'] = 1;
		$result = $this->load->table('developer')->add($data);
		if($result === false){
			$this->error = $this->load->table('developer')->getError();
			return false;
		}
		cache('developer',NULL);
		return array('appid' => $data['appid'],'secret' => $data['secret']);
	}
	/**
	 * 删除账号
	 */
	public function delete($appid){
		$sqlmap = array();
		$sqlmap['appid'] = $appid;
		$result = $this->load->table('developer')->delete($sqlmap);
		cache('developer',NULL);
		if($result === false){
			$this->error = $this->load->table('developer')->getError();
			return false;
		}
		return true;
	}
	/**
	 * 改变状态
	 */
	public function status($appid){
		$sqlmap = $data = array();
		$sqlmap['appid'] = $appid;
		$data['status']=array('exp',' 1-status ');
		$result = $this->load->table('developer')->where($sqlmap)->save($data);
		cache('developer',NULL);
		if($result === false){
			$this->error = $this->load->table('developer')->getError();
			return false;
		}
		return true;
	}
	/**
	 * 获取开发者账号列表
	 */
	public function lists(){
		$lists = $this->load->table('api/developer')->cache('developer',3600)->select();
		return $lists;
	}
}