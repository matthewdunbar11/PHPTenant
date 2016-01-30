<?php
namespace PHPTenant;
use \PDO;

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

		if(is_array($item)) {
			foreach($item as $key => $value) {

				$this->{$key} = $value;
			}
		}
		else {
			return false;
		}
		return true;
	}

	public function delete() {
		$stmt = $this->pdo->prepare("DELETE FROM " . static::$tableName . " WHERE ID = :ID");
		$stmt->bindParam(":ID", $this->ID);
		return $stmt->execute();
	}

	public function save() {
		echo 'Saving';
		echo $this->ID;
		if(isset($this->ID)) {
			$result = $this->update();
		}
		else {
			$fields = $this->getColumns();
			$fieldNames = array_keys($fields);
			$fieldValues = array_values($fields);

			$columns = "`" . implode("`, `", $fieldNames) . "`";
			$values = ':' . implode(', :', $fieldNames);			
			$sql = "INSERT INTO " . static::$tableName . " (" . $columns . ") VALUES (" . $values . ")";
			$stmt = $this->pdo->prepare($sql);
			foreach($fields as $field => $data) {
				$stmt->bindParam(':' . $field, $fields[$field]);
			}
			$result = $stmt->execute();
			$this->ID = $this->pdo->lastInsertId();
		}

		return $result;
	}

	public function update() {
		$fields = $this->getColumns();
		$values = '';

		foreach($fields as $field => $value) {
			$values .= "$field='$value',";
		}
		$values = rtrim($values, ",");
		$tableName = static::$tableName;
		$ID = $this->ID;
		$sql = "UPDATE $tableName SET $values where ID=$ID;";
		$result = $this->pdo->exec($sql);

		return $result;
	}

	public function getColumns() {
		$fields = get_object_vars($this);
		unset($fields['pdo']);
		unset($fields['tableName']);
		unset($fields['columnTypes']);
		unset($fields['tenantID']);
		return $fields;
	}

	public function getError() {
		print_r($this->pdo->errorInfo());
	}

	protected function setup() {
		$tableName = static::$tableName;
		$this->pdo->exec('CREATE TABLE IF NOT EXISTS ' . $tableName . ' (`ID` int AUTO_INCREMENT, PRIMARY KEY (ID));');

		$fields = $this->getColumns();
		unset($fields['ID']);
		unset($fields['tenantID']);

		$stmt2 = $this->pdo->prepare("ALTER TABLE :tableName ADD :columnName :columnType;");
		$columnType = '';
		$stmt2->bindParam(':tableName', $tableName);
		foreach(array_keys($fields) as $field) {
			if(isset($this->columnTypes[$field])) {
				$type = $this->columnTypes[$field];
			}
			else {
				$type = 'string';
			}

			switch ($type) {
				case 'string':
					$columnType = 'VARCHAR (250)';
					break;
				case 'float':
					$columnType = 'FLOAT';
					break;
				case 'int':
					$columnType = 'INT';
					break;
				default:
					echo 'That column type is not supported';
					break;
			}
			$columnName = $field;
			$stmt2->bindValue(':columnName', $columnName);
			$stmt2->bindValue(':columnType', $columnType);			
			$result = $stmt2->execute();
			$this->pdo->exec("ALTER TABLE $tableName ADD $columnName $columnType;");
			if(!$result) {

			}
		}

		$existingColumns = $this->pdo->query("DESCRIBE $tableName;");

		if($existingColumns) {
			foreach($existingColumns as $column) {
				if($column['Field'] != 'ID' && $column['Field'] != 'tenantID') {
					if(!array_key_exists($column['Field'], $fields)) {
						$this->pdo->exec("ALTER TABLE " . $tableName . " DROP COLUMN " . $column['Field'] . ";");
					}
				}
			}
		}
	}
}