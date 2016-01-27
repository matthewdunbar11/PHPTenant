<?php
class DBBaseTest extends PHPUnit_Framework_TestCase {
	public function testGetColumns() {
		$db_base = new DB_Base();
		$this->assertEquals($db_base->getColumns(), array('ID' => null, 'tenantID' => null));
	}

	public function testDBFunctions() {
		$db = new DB_Interface();
		foreach($models = $db->show('testmodel') as $model) {
			$model->delete();
		}
		$tests = $db->show('testmodel');
		$this->assertEquals(count($tests), 0);
		for($i = 1; $i <= 10; $i++) {
			$model = new TestModel();
			$model->name = 'This is a test';
			$model->price = 1;
			$model->size = '5';
			$model->save();
			$tests = $db->show('testmodel');
			$this->assertEquals(count($tests), $i);
		}
		$models = $db->show('testmodel');
		for( $i = 0; $i < count($models); $i++) {
			$model = $models[$i];
			$model->delete();

			$tests = $db->show('testmodel');
			$this->assertEquals(count($tests), count($models) - $i - 1);
		}
	}
}