<?php

class AuthController extends Controller
{

	public function index()
	{
		$this->view('auth/index');
	}

	public function login()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$username = $_POST['username'];
			$password = $_POST['password'];

			$userModel = $this->model("User");
			$user = $userModel->login($username, $password);

			if ($user) {
				echo "success";
			} else {
				$this->view('auth/index', ['errorMsg' => "wrong username or password"]);
			}
		} else {
			$this->view('auth/index');

		}
	}

	public function register()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$username = $_POST['username'];
			$email = $_POST['email'];
			$password = $_POST['password'];

			if (!$username || !$email || !$password) {
				echo "cant have empty fields";
			}

			$userModel = $this->model("User");

			$result = $userModel->createUser($username, $email, $password);

			if ($result) {
				echo "sucesso";

			} else {
				$errorMsg = "username or email already in use";
				$this->view('auth/register', ['errorMsg' => $errorMsg]);
			}
		} else {
			$this->view('auth/register');
		}
	}
}