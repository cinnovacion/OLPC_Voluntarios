<?php

namespace ZamoraTeran\Repository;

class disponibilidadRepository extends \Knp\Repository {

	public function getTableName() {
		return 'users';
	}

	public function findAll(){
		return $this->db->fetchAll('SELECT * FROM disponibilidad');
	}
}

//EOF