<?php

class DashboardController extends Controller
{

	public function index()
	{
		checkLogin();
		$this->view("dashboard/index");
	}

	public function upload()
	{

		checkLogin();
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			header('Location: /dashboard');
			exit;
		}
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			// print_r($_POST);
			$image = $_POST['image'];
			$this->view("dashboard/upload", ["image" => $image]);
		}
	}
}