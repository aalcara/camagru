<?php

require_once '../models/User.php';

class HomeController extends Controller
{
	public function index()
	{
		$user_model = new User();
        $users = $user_model->getAllUsers();

		$this->view('home/index', ['users' => $users]);
	}
}
