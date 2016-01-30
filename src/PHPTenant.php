<?php
namespace PHPTenant;

class PHPTenant {
	private $router;
	public function __construct() {
		$this->router = Router::getInstance();
	}

	public function handleRequest() {
		$path = substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), 1);
		$pathParts = explode("/", $path);
		$actionFound = false;
		$actions = $this->router->negotiate($path);
		if(is_array($actions)) {
			foreach($actions['actions'] as $action) {
				if(is_array($action)) {
					$class = $action[0];
					$method = $action[1];
					$class->{$method}($actions['args']);
					$actionFound = true;
				}
				else {
					$action($actions['args']);
					$actionFound = true;
				}
			}
		}
		if(!$actionFound) {
			echo 'No route was successfully found';
		}
	}
}