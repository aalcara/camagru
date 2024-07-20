<?php
class User extends Model
{
	public function createUser($username, $email, $password)
	{
		$userId = uniqid("", true);
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		$stmt = $this->db->prepare("INSERT INTO users (id, username, email, password) VALUES (?, ?, ?, ?)");
		$stmt->bind_param('ssss',$userId, $username, $email, $hashedPassword);
		return $stmt->execute();
	}

	public function getUser($id)
	{
		$stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
		$stmt->bind_param('s', $id);
		$stmt->execute();
		return $stmt->get_result()->fetch_assoc();
	}

	public function login($username, $password)
	{
		$stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$result = $stmt->get_result()->fetch_assoc();

		if ($result && password_verify($password, $result['password'])) {
			return $result;
		} else {
			return null;
		}
	}

	public function getAllUsers()
	{
		$stmt = $this->db->prepare("SELECT * FROM users");

		$stmt->execute();
		$result = $stmt->get_result();

		return $result->fetch_all(MYSQLI_ASSOC);
	}
}
