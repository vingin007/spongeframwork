<?php
require_cache(APP_PATH.config('DEFAULT_H_LAYER').'/api/library/api_abstract.class.php');
class api_factory{

	public function __construct($module = '', $server = 'api', $method_args) {
		$this->getInstance($module, $server, $method_args);
	}

    /**
     * 实例化驱动
     * @param type $module
     */
    public function getInstance($module = '', $type = 'module', $file = '', $server = 'api') {
        if($type == 'module'){
            $class_path = APP_PATH.config('DEFAULT_H_LAYER').'/'.$module.'/'.$server.'/'.$file.'_'.$server.'.class.php';
        }elseif($type == 'plugin'){
            $class_path = PLUGIN_PATH.$plugin.'/'.$server.'/'.$module.'_'.$server.'.class.php';
        }
        require_cache($class_path);
        $class = $file.'_'.$server;
        $this->instance = new $class();
        if ($this->instance->get_code() !== 200) {
            $this->code = $this->instance->get_code();
            $this->msg  = $this->instance->get_msg();
            return FALSE;
        }
        return $this->instance;
    }

    public function __call($method_name, $method_args) {
        if (method_exists($this, $method_name)) {
            return call_user_func_array(array(& $this, $method_name), $method_args);
        } elseif (!empty($this->instance) && ($this->instance instanceof api_abstract) && method_exists($this->instance, $method_name)) {
            return call_user_func_array(array(& $this->instance, $method_name), $method_args);
        }
    }
}