<?php
use PHPTenant\DB_Base;
class StudentModel extends DB_Base {
	public static $tableName = 'student';
	public $firstName;
	public $lastName;
	public $address1;
	public $address2;
	public $city;
	public $state;
	public $zip;
}