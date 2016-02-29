<?php

namespace ZamoraTeran\Repository;

class personaRepository extends \Knp\Repository {

	public function getTableName() {
		return 'persona';
	}

	public function find($id){
		return $this->db->fetchAssoc('SELECT * FROM persona WHERE idPersona = ?',array($id));
	}


	public function count(){
		return $this->db->fetchAssoc('SELECT count(*) as count FROM persona');
	}

	public function findAll($curpage,$numItemsPerPage){
		return $this->db->fetchAll('SELECT * FROM persona LIMIT ' . (int) (($curpage - 1) * $numItemsPerPage) . ',' .
			(int) ($numItemsPerPage));
	}

	public function findAllByString($search,$curpage,$numItemsPerPage){
		if($search == null){
			return $this->findAll();
		}
		return $this->db->fetchAll('SELECT * FROM persona WHERE 
			nombre LIKE "%'.$search.'%" OR
			NoDeCedula LIKE "%'.$search.'%" OR
			DireccionDeResidencia LIKE "%'.$search.'%" OR
			Telefono LIKE "%'.$search.'%" OR
			CorreoElectronico LIKE "%'.$search.'%" OR
			InstitucionAcademica LIKE "%'.$search.'%" OR
			CarreraCurso LIKE "%'.$search.'%"
			LIMIT ' . (int) (($curpage - 1) * $numItemsPerPage) . ',' .
			(int) ($numItemsPerPage));
	}

	public function getIdByCedula($cedula){
		return $this->db->fetchAssoc('SELECT idPersona as id FROM persona WHERE NoDeCedula = ?',array($cedula));
	}

	public function getIdByName($name){
		return $this->db->fetchAssoc('SELECT idPersona AS id FROM persona WHERE Nombre = ?',array($name));
	}

	
}

//EOF
