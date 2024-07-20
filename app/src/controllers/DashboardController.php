<?php

class DashboardController extends Controller
{
	private static $canvasWidth;
	private static $canvasHeight;

	public function __construct()
	{
		checkLogin();
		$this->canvasWidth = 320;
		$this->canvasHeight = 240;
	}

	public function index()
	{
		$this->view("dashboard/index", ["canvasWidth" => $this->canvasWidth, "canvasHeight" => $this->canvasHeight]);
	}

	public function upload()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			header('Location: /dashboard');
			exit;
		}
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			// print_r($_POST);
			$imageData = $_POST['image'];
			$imageId = uniqid();

			[$type, $imageData] = explode(';', $imageData);
            [, $imageData]      = explode(',', $imageData);

            $imageData = base64_decode($imageData);

			$filePath = 'uploads/' . $imageId . '.png';
            file_put_contents($filePath, $imageData);
			echo "sucesso";
		}
	}
}