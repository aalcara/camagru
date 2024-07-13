<?php

class DashboardController extends Controller {

	public function index() {

		checkLogin();
		$this->view("dashboard/index");
	}
}