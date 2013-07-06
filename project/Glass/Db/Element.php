<?php

namespace Glass\Db;

class Element extends Abstrct {
		
	public function addElement($listId, $name) {
		$sql = 'INSERT INTO elements (list_id, element_name) VALUES (:list_id, :element_name)';
		$stmt = $this->db->prepare($sql);
		$stmt->execute(array(
			'list_id' => $listId,
			'element_name' => $name
		));
		$newId = $this->db->lastInsertId();
		return $newId;
	}
	
	public function getElement($id) {
		$sql = 'SELECT * FROM elements WHERE element_id = :id';
		$stmt = $this->db->prepare($sql);
		$stmt->execute(array(
			'id' => $id
		));
		$row = $stmt->fetch();
		return $row;
	}
	
		
}

?>