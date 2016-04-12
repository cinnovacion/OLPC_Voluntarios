<?php

namespace ZamoraTeran\Repository;

class disponibilidadRepository extends \Knp\Repository {

	public function getTableName() {
		return 'disponibilidad';
	}

	public function findAll(){
		return $this->db->fetchAll('SELECT * FROM disponibilidad');
	}

	public function getDisponibilidad($id){
		return $this->db->fetchAll('SELECT * FROM disponibilidad WHERE Persona_idPersona = ?',array($id));
	}

	
}

//EOF