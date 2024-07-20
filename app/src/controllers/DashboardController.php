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

	public function upload($params)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			// TODO: ler alguma image pelo id
			echo $params;
			// header('Location: /dashboard');
			exit;
		}
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			if (!isset($_POST['image'])) {
				echo "No image data recieved";
			}
			$imageData = $_POST['image'];

			[$type, $imageData] = explode(';', $imageData);
			[, $imageData] = explode(',', $imageData);

			$fileHash = hash('sha256', $imageData);
			$filePath = "../../uploads/{$fileHash}.png";

			$imageModel = $this->model("Image");
			$imageId = $imageModel->createImage($_SESSION['user_id'], $fileHash);

			if (file_put_contents($filePath, $imageData) && $imageId)  {
				header("Location: /dashboard/upload?imageId={$imageId}");
				exit();
			} else {
				echo "Fail uploading image";
			}
		}
	}
}