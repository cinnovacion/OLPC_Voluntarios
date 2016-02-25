<?php

namespace ZamoraTeran\Repository;

class adminsRepository extends \Knp\Repository {

	public function getTableName() {
		return 'admins';
	}

	public function findAll(){
		return $this->db->fetchAll('SELECT * FROM admins');
	}
}

//EOF