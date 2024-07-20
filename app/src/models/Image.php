<?php
class Image extends Model
{

	public function createImage($ownerId, $hash)
	{
		$imageId = uniqid("", true);

		$stmt = $this->db->prepare("INSERT INTO images (id, owner_id, hash) VALUES (?, ?, UNHEX(?))");
		$stmt->bind_param('sss', $imageId, $ownerId, $hash);

		if ($stmt->execute()) {
			return $imageId;
		} else {
			return false;
		}
	}

	public function getImage($id)
	{
		$stmt = $this->db->prepare('SELECT * FROM images WHERE id = ?');
		$stmt->bind_param('s', $id);

		$stmt->execute();
		return $stmt->get_result()->fetch_assoc();
	}
}
