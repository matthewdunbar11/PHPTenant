<?php
use PHPTenant\Router;
use PHPTenant\Controller;

class Product extends Controller {
	public function routes($router) {		
		$router->register('index.php', array($this, 'test'));
		$router->register('products', array($this, 'all'));
		$router->register('product/new', array($this, 'add'));
		$router->register('product/create', array($this, 'create'));
		$router->register('product/:id', array($this, 'retrieve'));
		$router->register('product/:id/edit', array($this, 'edit'));
		$router->register('product/:id/update', array($this, 'update'));
		$router->register('product/:id/delete', array($this, 'delete'));
	}
	
	public function test() {
		echo 'Requested index';
	}
}