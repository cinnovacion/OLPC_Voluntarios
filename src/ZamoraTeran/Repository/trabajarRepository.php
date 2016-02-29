<?php

namespace ZamoraTeran\Repository;

class trabajarRepository extends \Knp\Repository {

	public function getTableName() {
		return 'trabajar';
	}

	public function findAll(){
		return $this->db->fetchAll('SELECT * FROM trabajar');
	}

	public function count(){
		return $this->db->fetchAssoc('SELECT count(*) as count FROM trabajar');
	}

	public function findByPersona($id,$curpage,$numItemsPerPage){
		return $this->db->fetchAll('SELECT * FROM trabajar WHERE Persona_idPersona = ? LIMIT ' . (int) (($curpage - 1) * $numItemsPerPage) . ',' .
			(int) ($numItemsPerPage), array($id));
	}

	public function findTotalHoursByPersona($id){
		return $this->db->fetchAssoc('SELECT Persona_idPersona, SUM(tiempo) AS Total FROM trabajar WHERE Persona_idPersona = ?', array($id));
	}

	public function getHoursOfWork(){
		return $this->db->fetchAll('SELECT Persona_idPersona, sum(tiempo) as tiempo FROM trabajar group by 
Persona_idPersona');
	}
}

//EOF