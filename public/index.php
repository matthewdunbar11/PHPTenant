<?php
require('../vendor/autoload.php');
require('controllers/product.php');
require('models/productmodel.php');

use PHPTenant\PHPTenant;

$PHPTenant = new PHPTenant();
$Product = new Product();
$PHPTenant->handleRequest();