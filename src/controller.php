<?php
namespace PHPTenant;
use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_Filter_Function;

class Controller {
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

	protected function getSingularName() {
		return get_class($this);
	}

	protected function getPluralName() {
		return get_class($this) . 's';
	}

	public function add() {
		$singularName = $this->getSingularName();
		echo $this->template->render("$singularName/new.html");
	}

	public function create() {
		$modelName = $this->getModelName();
		$modelInstance = new $modelName();
		foreach($_POST as $field => $value) {
			$modelInstance->{$field} = $value;
		}

		$modelInstance->save();
		$singularName = $this->getSingularName();
		$redirect = 'Location: ' . $this->viewLink($modelInstance->ID);
		header($redirect);
	}

	public function retrieve($args) {
		$modelName = $this->getModelName();
		$singularName = $this->getSingularName();
		$modelInstance = new $modelName($args['id']);
		echo $this->template->render("$singularName/single.html", array(
			'Delete' => $this->deleteLink($args['id']),
			'Edit' => $this->editLink($args['id']),
			'View' => $this->viewLink($args['id']),
			$singularName => $modelInstance
		));
	}

	public function edit($args) {
		$modelName = $this->getModelName();
		$singularName = $this->getSingularName();
		$modelInstance = new $modelName($args['id']);
		echo $this->template->render("$singularName/edit.html", array(
			$singularName => $modelInstance
		));
	}

	public function update($args) {
		$modelName = $this->getModelName();
		$model = new $modelName($args['id']);
		foreach($_POST as $field => $value) {
			$model->{$field} = $value;
		}
		$model->save();
		$redirect = 'Location: ' . $this->editLink($args['id']);

		header($redirect);
	}

	public function all($args) {
		$dbinterface = new DB_Interface();
		$modelName = $this->getModelName();
		$singularName = $this->getSingularName();
		$pluralName = $this->getPluralName();
		$items = $dbinterface->show($modelName);
		echo $this->template->render("$singularName/list.html", array($pluralName => $items));
	}

	public function viewLink($id) {
		$singularName = $this->getSingularName();
		return 'http://192.168.33.10/' . strtolower($singularName) . '/' . $id;
	}

	public function deleteLink($id) {
		$singularName = $this->getSingularName();
		return 'http://192.168.33.10/' . strtolower($singularName) . '/' . $id . '/delete';
	}

	public function editLink($id) {
		$singularName = $this->getSingularName();
		return 'http://192.168.33.10/' . strtolower($singularName) . '/' . $id . '/edit';
	}
}