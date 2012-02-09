<?php
App::uses('Model', 'Model');
 
class AppModel extends Model {

	public $actsAs = array('Containable');

	function paginateCount($conditions = array(), $recursive = 0, $extra = array()) {
		$parameters = compact('conditions');
		$find = '_findCount';
		if (isset($extra['type'])) {
			$extra['operation'] = 'count';
			$find = '_find' . Inflector::camelize($extra['type']);
			$params = $this->$find('before', array_merge($parameters, $extra));
			unset($params['fields']);
			unset($params['limit']);
			return $this->find('count', $params);
		}
		return $this->find('count', array_merge($parameters, $extra));
	}

   
    protected function cachePrefix() {
        $plugin = $this->plugin ? $this->plugin . '.' : '';
        return $plugin . $this->alias;
    }
   
    protected function cacheFind($prefix, $type = 'first', $params = array()) {
        $key = sha1(json_encode(array($prefix, $type, $params)));
        if (!($result = Cache::read($key))) {
            $result = parent::find($type, $params);
            Cache::write($key, $result);
           
            $storeKey = 'store_' . $prefix;
            if (!($store = Cache::read($storeKey))) {
                $store = array();
            }
            $store[$key] = true;
            Cache::write($storeKey, $store);
        }
        return $result;
    }
   
    protected function _clearCache($type = null) {
        foreach (Cache::read($this->cachePrefix()) as $key => $value) {
            Cache::delete($key);
        }
        return parent::_clearCache();
    }
	
}
