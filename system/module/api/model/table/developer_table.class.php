<?php
/**
 *		api数据层
 *      [Haidao] (C)2013-2099 Dmibox Science and technology co., LTD.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.haidao.la
 *      tel:400-600-2042
 */

class developer_table extends table {
	protected $_validate = array();
    protected $_auto = array();
    protected function _after_select(&$result, $options) {
    	$lists = array();
		foreach ($result as $list) {
			$lists[$list['appid']] = $list;
		}
		return $lists;
	}
}