<?php

namespace ZamoraTeran\Repository;

class personaRepository extends \Knp\Repository {

	public function getTableName() {
		return 'persona';
	}

	public function find($id){
		return $this->db->fetchAssoc('SELECT * FROM persona WHERE idPersona = ?',array($id));
	}

	public function findAll(){
		return $this->db->fetchAll('SELECT * FROM persona');
	}

	public function findAllByString($search){
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
			');
	}

	
}

//EOF
