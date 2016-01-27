<?php

class DB_Base {
	protected $pdo;
	public $ID;
	public $tenantID;

	public function __construct($id = 0) {
		$this->pdo = new PDO('mysql:host=localhost;dbname=scotchbox', 'root', 'root');
		if($id) {
			$this->load($id);
		}
		//$dbh = new PDO('sqlite:./db.db');
		//$this->pdo = $dbh;
		//$this->createDB();
		//$this->setup();



	}

	public function getID() {
		return $this->ID;
	}
	
	public function getTenantID() {
		return $this->tenantID;
	}

	public function createDB() {
		$createTable = $this->pdo->exec("CREATE DATABASE mydb;");
		$this->getError();	
	}

	protected function load($ID) {
		$sql = 'SELECT * FROM ' . static::$tableName . ' WHERE ID = :ID';
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':ID', $ID);
		$result = $stmt->execute();
		$item = $stmt->fetch(PDO::FETCH_ASSOC);

		foreach($item as $key => $value) {

			$this->{$key} = $value;
		}

		return true;
	}

	public function delete() {
		$stmt = $this->pdo->prepare("DELETE FROM " . static::$tableName . " WHERE ID = :ID");
		$stmt->bindParam(":ID", $this->ID);
		return $stmt->execute();
	}

	public function save() {
		$fields = $this->getColumns();
		$fieldNames = array_keys($fields);
		$fieldValues = array_values($fields);

		$columns = "`" . implode("`, `", $fieldNames) . "`";
		$values = ':' . implode(', :', $fieldNames);
		$stmt = $this->pdo->prepare("INSERT INTO " . static::$tableName . " (" . $columns . ") VALUES (" . $values . ")");
		foreach($fields as $field => $data) {
			$stmt->bindParam(':' . $field, $fields[$field]);
		}
		$result = $stmt->execute();
		$this->ID = $this->pdo->lastInsertId();
		return $result;
	}

	public function getColumns() {
		$fields = get_object_vars($this);
		unset($fields['pdo']);
		unset($fields['tableName']);
		return $fields;
	}

	public function getError() {
		print_r($this->pdo->errorInfo());
	}
}