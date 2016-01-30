<?php
require('../vendor/autoload.php');
require('controllers/product.php');
require('controllers/student.php');
require('models/productmodel.php');
require('models/studentmodel.php');

use PHPTenant\PHPTenant;


$PHPTenant = new PHPTenant();
$Product = new Product();
$Student = new Student();

$PHPTenant->handleRequest();