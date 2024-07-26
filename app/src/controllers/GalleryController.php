<?php

class GalleryController extends Controller
{
	public function index($params = [])
	{
		$image_model = $this->model("Image");

		$page = isset($params['page']) ? (int) $params['page'] : 1;
		$limit = 5;

		$images = $image_model->getPaginatedImages($limit, $page);
		$total_images = $image_model->getTotalImages();
		$total_pages = ceil($total_images / $limit);

		$this->view("gallery/index", [
			'images' => $images,
			'page' => $page,
			'total_pages' => $total_pages
		]);
	}
}
