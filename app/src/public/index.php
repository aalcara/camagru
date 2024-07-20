<?php
require_once '../config.php';
require_once '../controllers/Controller.php';
require_once '../models/Model.php';
require_once '../helpers/session_helper.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_uri = filter_var($request_uri, FILTER_SANITIZE_URL);

[$request_uri, $query_string] = explode('?', $request_uri)+ [1 => ''];

$request_uri = $request_uri == "/" ? "home" : trim($request_uri, '/');
$url = explode('/', $request_uri);

$controller_name = ucfirst(array_shift($url)) . 'Controller';
$method_name = array_shift($url) ?? 'index';

parse_str($query_string, $params);

if (file_exists("../controllers/$controller_name.php")) {
	require_once "../controllers/$controller_name.php";
	$controller = new $controller_name();
	if (method_exists($controller, $method_name)) {
		call_user_func_array([$controller, $method_name], [$params]);
		exit;
	}
}
require_once "../controllers/ErrorController.php";
call_user_func_array([new ErrorController(), "error404"], [$params]);
