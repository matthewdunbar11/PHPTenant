<?php


class User {
	private $dbh;
	private $config;
	private $auth;

	public function __construct() {
		$this->dbh = new PDO('mysql:host=localhost;dbname=scotchbox', 'root', 'root');
		$this->config = new PHPAuth\Config($this->dbh);
		$this->auth   = new PHPAuth\Auth($this->dbh, $this->config);
	}

	private function setup() {
		$sql = file_get_contents('vendor/phpauth/phpauth/database_mysql.sql');
		$qr = $this->dbh->exec($sql);
	}

	public function login($id, $password, $rememberMe) {
		$login = $this->auth->login($id, $password, $rememberMe);
		setcookie($this->config->cookie_name, $login['hash'], $login['expire'], $this->config->cookie_path, $this->config->cookie_domain, $this->config->cookie_secure, $this->config->cookie_http);
		return $login;
	}

	public function register($id, $password, $passwordConfirm) {
		$result = $this->auth->register($id, $password, $passwordConfirm);
		return $result;
	}

	public function getUser() {
		$uid = $this->auth->getSessionUID($_COOKIE[$this->config->cookie_name]);

		$user = $this->auth->getUser($uid);

		return $user;
	}
}

