<?php


class Router {
	private static $instance;
	private $routingElements = array();

	public static function getInstance($reset = false) {
		if (null === Router::$instance || $reset) {
			Router::$instance = new Router();
		}

		return Router::$instance;
	}

	protected function __construct() {

	}

	public function register($path, $action) {
		$pathParts = explode("/", $path);
		$traversedElement = &$this->routingElements;

		while(count($pathParts)) {
			$currentPart = array_shift($pathParts);
			if($currentPart[0] == ':') {
				$secondPart = substr($currentPart, 1);
				$currentPart = ':';
				array_unshift($pathParts, $secondPart);
			}
			if(!isset($traversedElement[$currentPart]) || !is_array($traversedElement[$currentPart])) {
				$traversedElement[$currentPart] = array();
			}
			$traversedElement = &$traversedElement[$currentPart];
		}
		if(!isset($traversedElement['_']) || !is_array($traversedElement['_'])) {
			$traversedElement['_'] = array();
		}
		$traversedElement['_'][] = $action;

	}

	public function routes() {
		return $this->routingElements;
	}

	public function negotiate($path) {
		$pathParts = explode("/", $path);
		$traversedElement = &$this->routingElements;
		$placeholderValues = array();
		while(count($pathParts)) {
			$currentPart = array_shift($pathParts);
			if(!isset($traversedElement[$currentPart]) || !is_array($traversedElement[$currentPart])) {
				if(isset($traversedElement[':']) && is_array($traversedElement[':'])) {
					
					$placeholderKey = key($traversedElement[':']);
					$placeholderValues[$placeholderKey] = $currentPart;
					$traversedElement = &$traversedElement[':'];
					$currentPart = $placeholderKey;
				}
				else {
					return false;
				}
			}
			$traversedElement = &$traversedElement[$currentPart];
		}
		if(!isset($traversedElement['_']) || !is_array($traversedElement['_'])) {
			return false;
		}
		return array(
			'actions' => $traversedElement['_'],
			'args' => $placeholderValues
		);
	}
}