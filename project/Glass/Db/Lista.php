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
	
	public function getList($id) {
		$sql = 'SELECT * FROM lists WHERE list_id = :id';
		$stmt = $this->db->prepare($sql);
		$stmt->execute(array(
			'id' => $id
		));
		$row = $stmt->fetch();
		return $row;
	}	
	
	public function getListElements($id){
		$sql = 'SELECT * FROM elements WHERE list_id = :id';
		$stmt = $this->db->prepare($sql);
		$stmt->execute(array(
			'id' => $id
		));
		$rows = $stmt->fetchAll();
		return $rows;
	}
}

?>
