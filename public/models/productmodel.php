<?php
use PHPTenant\DB_Base;
class ProductModel extends DB_Base {
	public static $tableName = 'product';	
	public $name;
	public $price;
	public $size;

	public $columnTypes = array(
		'name' => 'string',
		'price' => 'float',
		'size' => 'string'
	);

	public function asetup() {
		$columns = "ID int NOT NULL AUTO_INCREMENT, name VARCHAR( 250 ), price VARCHAR( 250), size VARCHAR( 250), tenantID int, PRIMARY KEY (ID)" ;


		$this->pdo->exec("CREATE TABLE IF NOT EXISTS " . self::$tableName . " ($columns)");
		$this->pdo->exec("ALTER TABLE " . self::$tableName . " ADD ID NOT NULL AUTO_INCREMENT;");
		$this->pdo->exec("ALTER TABLE " . self::$tableName . " ADD name VARCHAR( 250 );");
		$this->pdo->exec("ALTER TABLE " . self::$tableName . " ADD price VARCHAR( 250 );");
		$this->pdo->exec("ALTER TABLE " . self::$tableName . " ADD size VARCHAR( 250 );");
		$this->pdo->exec("ALTER TABLE " . self::$tableName . " ADD tenantID int;");
	}

}