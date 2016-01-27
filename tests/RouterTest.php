<?php
class RouterTest extends PHPUnit_Framework_TestCase {
	public function testRoutesRegister() {
		$router = Router::getInstance(true);

		$router->register('abc', 'test');
		$this->assertEquals($router->routes(), array('abc' => array('_' => array('test'))));
	}

	public function testRoutesRegisterMultiple() {
		$router = Router::getInstance(true);

		$router->register('abc', 'test');
		$router->register('abc', 'test2');
		$router->register('abc/xyz', 'test3');
		$router->register('xyz/abc', 'test4');

		$this->assertEquals($router->routes(), array(
			'abc' => array(
				'_' => array(
					'test',
					'test2'
				),
				'xyz' => array(
					'_' => array(
						'test3'
					)
				)
			),
			'xyz' => array(
				'abc' => array(
					'_' => array(
						'test4'
					)
				)
			)
		));
	}

	public function testRoutesReturnCorrectAction() {
		$router = Router::getInstance(true);

		$router->register('abc/xyz', 'test');
		$router->register('abc', 'test2');
		$result = $router->negotiate('abc/xyz');

		$this->assertEquals($result, array('actions' => array('test'), 'args' => array()));
	}

	public function testRoutesFailOnBadRoute() {
		$router = Router::getInstance(true);

		$router->register('abc/xyz', 'test');
		$router->register('abc', 'test2');
		$result = $router->negotiate('abc/xyaz');

		$this->assertEquals($result, false);		
	}

	public function testSavesNamedPlaceholders() {
		$router = Router::getInstance(true);

		$router->register('abc/:id', 'test');
		$this->assertEquals($router->routes(), array(
			'abc' => array(
				':' => array(
					'id' => array(
						'_' => array(
							'test'
						)
					)
				)
			)
		));
	}

	public function testNegotiatesPlaceholders() {
		$router = Router::getInstance(true);

		$router->register('abc/:id', 'test');
		$result = $router->negotiate('abc/1');

		$this->assertEquals($result, array('actions' => array('test'), 'args' => array('id' => 1)));
	}
}