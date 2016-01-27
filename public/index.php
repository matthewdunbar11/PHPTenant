<?php
//include('autoload.php');

require('vendor/autoload.php');
spl_autoload_register(function ($class_name) {
    @ include 'classes/' . $class_name . '.php';
});

spl_autoload_register(function ($class_name) {
    @ include 'controllers/' . $class_name . '.php';
});

spl_autoload_register(function ($class_name) {
    @ include 'models/' . $class_name . '.php';
});

$PHPTenant = new PHPTenant();

$product = new Product();

$PHPTenant->handleRequest();

/*
function _require_all($dir, $depth=0) {
    if ($depth > $this->max_scan_depth) {
        return;
    }
    // require all php files
    $scan = glob("$dir/*");
    foreach ($scan as $path) {
        if (preg_match('/\.php$/', $path)) {
            require_once $path;
        }
        elseif (is_dir($path)) {
            $this->_require_all($path, $depth+1);
        }
    }
}*/

/*$dbinterface = new DB_Interface();
$product = $dbinterface->load('testmodel', 1);

$product->name = 'XYZ Spring';
$product->save();
print_r($product->ID);
print_r($product);	*//*
$testModel = new TestModel();

$testModel->name = 'Test';
$testModel->price = 1.5;
$testModel->size = '1';
$testModel->save();
$testModel->getError();*/