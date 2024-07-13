<?php
function checkLogin()
{
	session_start();
	if (!isset($_SESSION['user_id'])) {
		
		$_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
		
		header('Location: auth/login');
		exit();
	}
}
