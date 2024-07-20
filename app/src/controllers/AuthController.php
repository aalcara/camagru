<?php

class AuthController extends Controller
{

	public function index()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'GET' || $_SESSION['user_id']) {
			header('Location: /');
			exit();
		}
		$this->view('auth/index');
	}

	public function login()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SESSION['user_id']) {
			header('Location: /home');
			exit();
		}
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			$username = $_POST['username'];
			$password = $_POST['password'];

			$userModel = $this->model("User");
			$user = $userModel->login($username, $password);

			if ($user) {
				$_SESSION['user_id'] = $user['id'];
				$_SESSION['username'] = $user['username'];

				if (isset($_SESSION['redirect_url'])) {
					$redirectUrl = $_SESSION['redirect_url'];
					unset($_SESSION['redirect_url']);
					header("Location: $redirectUrl");
				} else {
					header('Location: /home');
				}
				exit();
			} else {
				$errorMsg = "Invalid username or password";
			}
		}
		$this->view('auth/index', ['errorMsg' => $errorMsg]);
	}

	public function signup()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			$this->view('auth/signup');
		}
		
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
				// TODO decidir o que fazer
				header('Location: /auth/login');
				exit();

			}
			$errorMsg = "username or email already in use";
			$this->view('auth/signup', ['errorMsg' => $errorMsg]);
		}
	}

	public function logout()
	{
		session_unset();
		session_destroy();
		header('Location: /home');
		exit;
	}
}