<?php
require_once '../config.php';
require_once '../controllers/Controller.php';
require_once '../models/Model.php';
require_once '../helpers/session_helper.php';

$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = filter_var($requestUri, FILTER_SANITIZE_URL);
[$requestUri, $params] = explode('?', $requestUri);
$requestUri = $requestUri == "/" ? "home" : trim($requestUri, '/');
$url = explode('/', $requestUri);

$controllerName = ucfirst(array_shift($url)) . 'Controller';
$methodName = array_shift($url) ?? 'index';
$params = explode('&', $params);

if (file_exists("../controllers/$controllerName.php")) {
	require_once "../controllers/$controllerName.php";
	$controller = new $controllerName();
	if (method_exists($controller, $methodName)) {
		call_user_func_array([$controller, $methodName], $params);
		exit;
	}
}
require_once "../controllers/ErrorController.php";
call_user_func_array([new ErrorController(), "error404"], $params);
