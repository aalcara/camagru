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

	public function upload($params = [])
	{
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			if (!isset($params['imageId'])) {
				echo "cannot found imageId";
				exit;
			}

			$imageModel = $this->model("Image");
			$image = $imageModel->getImage($params['imageId']);
			if (!isset($image)) {
				echo "cannot found image in the database";
				exit;
			}

			$imageHashHex = bin2hex($image['hash']);

			$filePath = "../../uploads/{$imageHashHex}.png";
			if (!file_exists($filePath)) {
				echo "File does not exist";
				exit;
			}
			$decodedImage = file_get_contents($filePath);
			$base64Image = base64_encode($decodedImage);
			$dataURL = "data:image/png;base64,{$base64Image}";

			$this->view("/dashboard/upload", ["image" => $dataURL]);
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (!isset($_POST['image'])) {
				echo "No image data recieved";
				exit;
			}
			$imageData = $_POST['image'];

			[, $imageData] = explode(';', $imageData);
			[, $imageData] = explode(',', $imageData);
			$decodedImage = base64_decode($imageData);

			$fileHash = hash('sha256', $decodedImage);
			$filePath = "../../uploads/{$fileHash}.png";

			if (!file_put_contents($filePath, $decodedImage)) {
				echo "Fail uploading image";
			}

			$imageModel = $this->model("Image");
			$imageId = $imageModel->createImage($_SESSION['user_id'], $fileHash);

			if (!$imageId) {
				echo "Fail saving image to databse";
				exit;
			}

			header("Location: /dashboard/upload?imageId={$imageId}");
		}
	}
}
