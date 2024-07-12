<?php
class User extends Model
{
	public function getUser($id)
	{
		$stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
		$stmt->bind_param('i', $id);
		$stmt->execute();
		return $stmt->get_result()->fetch_assoc();
	}
	public function getAllUsers()
	{
		$stmt = $this->db->prepare("SELECT * FROM users");

		$stmt->execute();
		$result = $stmt->get_result();

		return $result->fetch_all(MYSQLI_ASSOC);
	}
}
