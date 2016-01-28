<?php
use PHPTenant\Router;
class Product extends Component {
	public function routes($router) {		
		$router->register('products', 'test123');
		$router->register('index.php', array($this, 'test'));
		$router->register('products', array($this, 'all'));
		$router->register('product/:id', array($this, 'single'));
	}
	
	public function test() {
		echo 'Requested index';
	}

	public function all($args) {
		print_r('List called with');
		print_r($args);
	}
}

class Component {
	private $router;
	public function __construct() {
		$router = Router::getInstance();
		$modelName = $this->getModelName();
		$this->model = new $modelName();
		$this->routes($router);


		$loader = new Twig_Loader_Filesystem('./templates');
		$this->template = new Twig_Environment($loader, array(
		    //'cache' => './templates/cache/',
		));
		$this->template->addFilter('var_dump', new Twig_Filter_Function('var_dump'));

	}

	protected function getModelName() {
		return get_class($this) . 'Model';
	}

	protected function getTemplatePrefix() {
		return get_class($this);
	}

	public function single($args) {
		$modelName = $this->getModelName();
		$modelInstance = new $modelName($args['id']);
		echo $this->template->render('product/index.html', array($this->getModelName() => $modelInstance));
	}
}