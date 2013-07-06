<?php

namespace Glass;

class DbProvider {
	
	protected $dbInstances = array();
	
	/**
	 * Returns model class. All model instances are returned in signeton principle.
	 * @param string $name of a model to be returned.
	 * @return Db\Abstrct
	 * @throws FatalError when given modelClass is not found.
	 */
	public function __get($name) {
		return $this->_getInstance($name);
	}
	
	protected function _getInstance($name) {
		if(isset($this->dbInstances[$name]))
			return $this->dbInstances[$name];
		
		$className = '\\Glass\\Db\\' . ucfirst($name);
		
		return $this->dbInstances[$name] = new $className();
	}
	
	public function currency($name) {
		return new \Increment\Dataset\Currency($name);
	}
}