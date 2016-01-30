<?php
namespace PHPTenant;
use \PDO;
class DB_Interface {
	protected $pdo;
	public function __construct() {
		$this->pdo = new PDO('mysql:host=localhost;dbname=scotchbox', 'root', 'root');
		//$dbh = new PDO('sqlite:./db.db');
		//$this->pdo = $dbh;
		//$this->createDB();
		//$this->setup();
	}

	public function show($className) {
	    $sql = 'SELECT * FROM ' . $className::$tableName . ';';
	    $rows = array();
	    $results = $this->pdo->query($sql);
	    foreach($results as $row) {
	    	$tempObject = new $className();
	    	foreach($row as $field => $value) {
	    		if(!is_numeric($field)) {
	    			$tempObject->{$field} = $value;
    			}
	    	}
	    	$rows[] = $tempObject;
	    }

	    return $rows;
	}

	public function load($className, $ID) {
		return new $className($ID);
	}

	public function getError() {
		print_r($this->pdo->errorInfo());
	}	
}