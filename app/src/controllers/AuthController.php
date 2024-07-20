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

			// TODO: validar inputs
			$user_model = $this->model("User");
			$user = $user_model->login($username, $password);

			if ($user) {
				$_SESSION['user_id'] = $user['id'];
				$_SESSION['username'] = $user['username'];

				if (isset($_SESSION['redirect_url'])) {
					$redirect_url = $_SESSION['redirect_url'];
					unset($_SESSION['redirect_url']);
					header("Location: $redirect_url");
				} else {
					header('Location: /home');
				}
				exit();
			} else {
				$error_msg = "Invalid username or password";
			}
		}
		$this->view('auth/index', ['errorMsg' => $error_msg]);
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

			$user_model = $this->model("User");

			$result = $user_model->createUser($username, $email, $password);

			if ($result) {
				// TODO decidir o que fazer
				header('Location: /auth/login');
				exit();

			}
			$error_msg = "username or email already in use";
			$this->view('auth/signup', ['errorMsg' => $error_msg]);
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