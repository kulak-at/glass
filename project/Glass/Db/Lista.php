<?php

namespace Glass\Db;

class Lista extends Abstrct {
	
	protected $list_id = NULL;
	
	public function addList($name, $dupa08) {
		$sql = 'INSERT INTO lists (list_name, card_id) VALUES (:name,:dupa08)';
		$stmt = $this->db->prepare($sql);
		$stmt->execute(array(
			'name' => $name,
			'dupa08' => $dupa08
		));
		$newId = $this->db->lastInsertId();
		return $newId;
	}
		
		
}

?>
