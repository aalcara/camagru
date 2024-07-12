<?php
require_once '../config.php';
require_once '../controllers/Controller.php';
require_once '../models/Model.php';

$requestUri = $_SERVER['REQUEST_URI'];
echo "<br> requestUri: ", $requestUri;
$requestUri = filter_var($requestUri, FILTER_SANITIZE_URL);
echo "<br> requestUri: ", $requestUri;
$requestUri = $requestUri == "/" ? "home" : trim($requestUri, '/');
echo "<br> requestUri: ", $requestUri;
$url = explode('/', $requestUri);
for ($i = 0; $i < count($url); $i++) {
	echo "<br>url[$i]: ", $url[$i];
}

$controllerName = ucfirst(array_shift($url)) . 'Controller';
$methodName = array_shift($url) ?? 'index';
$params = $url;

if (file_exists("../controllers/$controllerName.php")) {
	require_once "../controllers/$controllerName.php";
	$controller = new $controllerName();
	if (method_exists($controller, $methodName)) {
		call_user_func_array([$controller, $methodName], $params);
	} else {
		echo "Method not found!";
	}
} else {
	echo "Controller not found!";
}