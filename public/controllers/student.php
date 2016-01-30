<?php
use PHPTenant\Router;
use PHPTenant\Controller;

class Student extends Controller {
	public function routes($router) {		
		$router->register('students', array($this, 'all'));
		$router->register('student/:id', array($this, 'single'));
	}
}