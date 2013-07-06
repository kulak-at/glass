<?php

namespace Glass\Db;

abstract class Abstrct {
	/**
	 * @var \PDO PDO Instance
	 */
	protected $db;
	
	public function __construct() {
		$this->db = \Glass\Db::getDb();
	}
	
	protected function _getConfig($el) {
		return \Glass\Db::getConfig($el);
	}
}