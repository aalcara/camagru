<?php

require_once '../models/User.php';

class HomeController extends Controller
{
	public function index()
	{
		$userModel = new User();
        $users = $userModel->getAllUsers();

		$this->view('home/index', ['users' => $users]);
	}
}
