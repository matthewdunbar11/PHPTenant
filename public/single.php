<!DOCTYPE html>
<html>
<head>
</head>
<body>
<pre>
<?php
	require('autoload.php');
	$interface = new DB_Interface();
	$products = $interface->load('Product', 1);

	print_r($products);

?>
</pre>
</body>
</html>