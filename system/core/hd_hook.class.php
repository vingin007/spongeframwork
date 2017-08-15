<?php
class hd_hook {

    static private  $hooks       =   array();

    /**
     * [add 注册hook]
     * @param [type]  $hook     [description]
     * @param [type]  $class [description]
     * @param boolean $first    [description]
     *
     *
     * hooks 格式 {
     *     key : {
     *         class 类型
     *     }
     *
     * }
     *
     *
     */
    static public function add(){
        if(cache('hooks')) return true;
        $apps = model('app/app','service')->get_apps();
        $types = array('module','plugin');
        $hooks = array();
        foreach ($apps as $app) {
            list($type, $appname) = explode('.', $app);
            if($type == 'module'){
                $path = APP_PATH.config('DEFAULT_H_LAYER').'/'.$appname.'/hooks/';
            }elseif ($type == 'plugin') {
                $path = APP_PATH.'plugin/'.$appname.'/hooks/';
            }
            $dir = dir($path);
            if(!$dir) continue;
            while ($entry = $dir->read()) {
                if(!in_array($entry, array('.', '..')) && is_file($path.$entry)){
                    require_cache($path.$entry);
                }
            }
        }
        foreach(get_declared_classes() AS $class) {
           $reflectionClass = new ReflectionClass($class);
            if(!$reflectionClass->isSubclassOf('plugin') && !$reflectionClass->isSubclassOf('hd_hook')) continue;
            $methods = $reflectionClass->getMethods();
            $file = $reflectionClass->getFileName();
            $classname = $reflectionClass->getName();
            $hook_type = '';
            foreach ($types AS $type) {
                if(strpos($classname,$type) === 0) $hook_type = $type;
            }
            $filename = basename($file);
            $filename = substr($filename, 0,strpos($filename, '.'));
            foreach ($methods AS $method) {
                if($method->class == 'hd_hook' || $method->class == 'plugin') continue;
                $length = strlen($hook_type);
                $_method = substr($method->class, $length+1);
                $_method = str_replace('_'.$filename, '', $_method);
                $hooks[$method->name][] = $hook_type.'/'.$_method.'/'.$filename;
            }
        }
        cache('hooks',$hooks,'common',array('expire' => 7200));
        return true;
    }
    /**
     * 执行钩子
     * @param string $hook 钩子名称
     * @param mixed $params 传入参数
     * @return void
     */
    static public function listen($hook,&$params = null) {
        if(!$hook) return FALSE;
        $hooks = cache('hooks');
        if(!$hooks) self::add();
        if(isset($hooks[$hook])) {
            foreach ($hooks[$hook] as $class) {
                list($type,$identify,$name) = explode('/',$class);
                $classname = str_replace("/", "_", $class);
                $filename = $name.EXT;

                if($type == 'module'){
                    $path = APP_PATH.config('DEFAULT_H_LAYER').'/'.$identify.'/hooks/';
                }elseif ($type == 'plugin') {
                    $path = APP_PATH.'plugin/'.$identify.'/hooks/';
                }
                if(require_cache($path.$filename)){
                    $result = self::class_exec($classname,$hook,$identify,$params);
                    if(is_array($result)){
                        $return[] = $result;
                    }elseif(is_string($result)){
                        $return .= $result;
                    }

                }
            }
            return $return;
        }
        return FALSE;
    }
    /**
     * [class_exec 执行类]
     * @param  [type] $class   [description]
     * @param  string $hook    [description]
     * @param  [type] &$params [description]
     * @return [type]          [description]
     */
    public static function class_exec($class, $hook = '',$identify = '', &$params = null){
        $obj = new $class($identify);
        if(is_callable(array($obj, $hook))){
            return  $obj->$hook($params);
        }
    }
}