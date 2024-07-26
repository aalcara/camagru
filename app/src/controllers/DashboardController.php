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
			"Mr. Bean" => "/images/mr-bean.png",
			"kitty" => "/images/kitty.png",
		];
	}

	public function index()
	{
		$this->view("dashboard/index", ["canvasWidth" => $this->canvas_width, "canvasHeight" => $this->canvas_height, "superposableImages" => $this->superposable_images]);
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

			$this->view("/dashboard/upload", ["image" => $image]);
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (!isset($_POST['image']) || !isset($_POST['superposableImage'])) {
				echo "No image data recieved";
				exit;
			}
			$image_data = $_POST['image'];
			$superposable_image_path = $_POST['superposableImage'];

			[, $image_data] = explode(';', $image_data);
			[, $image_data] = explode(',', $image_data);
			$decoded_image = base64_decode($image_data);

			$image = imagecreatefromstring($decoded_image);
			if ($image === false) {
				echo "Failed to create image from base64 data";
				exit;
			}

			$superposable_image = imagecreatefrompng(".{$superposable_image_path}");
			if ($superposable_image === false) {
				echo "Failed to load superposable image";
				exit;
			}

			$image_width = imagesx($image);
			$image_height = imagesy($image);

			imagealphablending($image, true);
			imagesavealpha($image, true);
			imagecopy($image, $superposable_image, 0, 0, 0, 0, $image_width, $image_height);

			ob_start();
			imagepng($image);
			$final_image_data = ob_get_contents();
			ob_end_clean();

			$file_hash = hash('sha256', $final_image_data);
			$file_path = "./uploads/{$file_hash}.png";
			if (!file_put_contents($file_path, $final_image_data)) {
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
