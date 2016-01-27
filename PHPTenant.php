<?php

class PHPTenant {
	private $router;
	public function __construct() {
		$this->router = Router::getInstance();
	}

	public function handleRequest() {
		$path = substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), 1);
		$pathParts = explode("/", $path);

		$actions = $this->router->negotiate($path);

		foreach($actions['actions'] as $action) {
			if(is_array($action)) {
				$class = $action[0];
				$method = $action[1];
				$class->{$method}($actions['args']);
			}
			else {
				$action($actions['args']);
			}
		}

	}
}