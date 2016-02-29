<?php

namespace ZamoraTeran\Repository;

class adminsRepository extends \Knp\Repository {

	public function getTableName() {
		return 'admins';
	}

	public function findAll(){
		return $this->db->fetchAll('SELECT * FROM admins');
	}

	public function getByName($nombre){
		return $this->db->fetchAssoc('SELECT * FROM admins WHERE Nombre = ?',array($nombre));
	}
	
}

//EOF