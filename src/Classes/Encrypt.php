<?php

/**
* Controller for the authors
* @author Robin Staes <robin.staes@odisee.be>
* @copyright (c) 2015 Robin Staes
* @package Service
* @version 1.0.7
*/
class Encrypt{

	// Characters for the salt generation
	private $_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	private $_saltLength = 10;
	private $_alg = 'sha256';

	/**
	 * Constructor
	 */
	public function __construct($saltLength = 10) {
		$this->_saltLength = $saltLength;	
	}

	/**
	 * Setter for the salt
	 * Accepts string
	 */
	public function setSalt($length) {
		$this->_saltLength = $length;
	}

	/**
	 * Setter for charset (random salt generation)
	 * Accepts string of characters
	 */
	public function setCharSet($charSet) {
		$this->_chars = $charSet;
	}

	/**
	 * Setter for the algorithm (default: sha256)
	 * Accepts string
	 */
	public function setAlgorithm($alg) {
		$this->_alg = $alg;
	}

	/**
	 * Encrypts a given password
	 * @var String $password
	 * @return the encrypted password
	 */
	public function encryptPassword($password) {
		$salt = $this->generateRandomString($this->_saltLength);
		$pass  = hash($this->_alg,$salt.$password);

		$secret = array('salt'=>$salt,
						'password'=>$pass);
		return $secret;
	}

	/**
	 * control a given password
	 * @var String $password
	 * @var String $passworDatabase
	 * @return If true or false password
	 */
	
	public function controlPassword($password, $passworDatabase) {
		$salt = substr($passworDatabase,0,20);
		$wachtwoord= substr($passworDatabase,20);
		$pass  = hash($this->_alg,$salt.$password);
		if($pass == $wachtwoord){
			return true;
		}
		return false;
	}

	/**
	 * get the base 32 character for the generated number
	 * @var String $num
	 * @return the base 32 character for the generated number
	 */
	private function getBase62Char($num) {
	    return $this->_chars[$num];
	}

	/**
	 * generate a random string using the character array
	 * @var String $nbLetters
	 * @return a random string using the character array
	 */
	private function generateRandomString($nbLetters){
	    $randString="";

	    for($i=0; $i < $nbLetters; $i++){
	        $randChar = $this->getBase62Char(mt_rand(0,61));
	        $randString .= $randChar;
	    }
	    return ($randString.time());
	}
}
?>