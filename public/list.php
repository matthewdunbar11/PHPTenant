<!DOCTYPE html>
<html>
<head>
</head>
<body>
<pre>
<?php
	require('vendor/autoload.php');
	require('autoload.php');

$user = new User();

print_r($user->getUser());

	$interface = new DB_Interface();
	$products = $interface->show('Product');

	print_r($products);

?>
</pre>
</body>
</html>