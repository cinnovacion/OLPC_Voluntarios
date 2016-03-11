<?php

namespace ZamoraTeran\Repository;

class adminsRepository extends \Knp\Repository {

	public function getTableName() {
		return 'admins';
	}

/**	public function findAll(){
		return $this->db->fetchAll('SELECT * FROM admins');
	}**/

	public function count(){
		return $this->db->fetchAssoc('SELECT count(*) as count FROM admins');
	}

	public function getByName($nombre){
		return $this->db->fetchAssoc('SELECT * FROM admins WHERE Nombre = ?',array($nombre));
	}

	public function getEmpleados(){
		return $this->db->fetchAll('SELECT * FROM admins');
	}

	
	public function getEmpleadoById($id){
		return $this->db->fetchAssoc('SELECT * FROM admins WHERE idAdmins = ?',array($id));
	}


	public function findAll($curpage,$numItemsPerPage){
		return $this->db->fetchAll('SELECT * FROM admins LIMIT ' . (int) (($curpage - 1) * $numItemsPerPage) . ',' .(int) ($numItemsPerPage));
	}

	public function countByString($search){
		return $this->db->fetchAssoc('SELECT count(*) as count FROM admins
			WHERE Nombre LIKE "%'.$search.'%"');
	}

	public function findAllByString($search,$curpage,$numItemsPerPage){
		if($search == null){
			return $this->findAll($curpage,$numItemsPerPage);
		}
		return $this->db->fetchAll('
			SELECT * FROM admins 
			WHERE Nombre LIKE "%'.$search.'%"
			LIMIT ' . (int) (($curpage - 1) * $numItemsPerPage) . ',' .
			(int) ($numItemsPerPage));
	}
}
//EOF
