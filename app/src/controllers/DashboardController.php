<?php

class DashboardController extends Controller
{
	private static $canvas_width;
	private static $canvas_height;
	private static $superposable_images;

	public function __construct()
	{
		checkLogin();
		$this->canvas_width = 320;
		$this->canvas_height = 240;
		$this->superposable_images = [
			"Mr. Bean" => "images/mr-bean.png",
			"kitty" => "images/kitty.png",
		];
	}

	public function index()
	{
		$this->view("dashboard/index", ["canvasWidth" => $this->canvas_width, "canvasHeight" => $this->canvas_height, "superposableImages"=> $this->superposable_images]);
	}

	public function upload($params = [])
	{
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			if (!isset($params['imageId'])) {
				echo "cannot found imageId";
				exit;
			}

			$image_model = $this->model("Image");
			$image = $image_model->getImage($params['imageId']);
			if (!isset($image)) {
				echo "cannot found image in the database";
				exit;
			}

			$image_hash_hex = bin2hex($image['hash']);

			$file_path = "../../uploads/{$image_hash_hex}.png";
			if (!file_exists($file_path)) {
				echo "File does not exist";
				exit;
			}
			$decoded_image = file_get_contents($file_path);
			$base64_image = base64_encode($decoded_image);
			$data_url = "data:image/png;base64,{$base64_image}";

			$this->view("/dashboard/upload", ["image" => $data_url]);
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (!isset($_POST['image'])) {
				echo "No image data recieved";
				exit;
			}
			$image_data = $_POST['image'];

			[, $image_data] = explode(';', $image_data);
			[, $image_data] = explode(',', $image_data);
			$decoded_image = base64_decode($image_data);

			$file_hash = hash('sha256', $decoded_image);
			$file_path = "../../uploads/{$file_hash}.png";

			if (!file_put_contents($file_path, $decoded_image)) {
				echo "Fail uploading image";
			}

			$image_model = $this->model("Image");
			$image_id = $image_model->createImage($_SESSION['user_id'], $file_hash);

			if (!$image_id) {
				echo "Fail saving image to databse";
				exit;
			}

			header("Location: /dashboard/upload?imageId={$image_id}");
		}
	}
}
