<?php
class Model
{
	protected $db;

	public function __construct()
	{
		$this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if ($this->db->connect_error) {
			die('Connection failed: ' . $this->db->connect_error);
		}

		if (!$this->db->select_db(DB_NAME)) {
			die("Failed on select databse: " . $this->db->error);
		}
	}
}
