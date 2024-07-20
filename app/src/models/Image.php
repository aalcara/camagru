<?php
class Image extends Model
{

	public function createImage($ownerId, $hash) {
		$imageId = uniqid("", true);

		$stmt = $this->db->prepare("INSERT INTO images (id, owner_id, hash) VALUES (?, ?, UNHEX(?))");
		$stmt->bind_param('sss', $imageId, $ownerId, $hash);

		if ($stmt->execute()) {
			return $imageId;
		} else {
			return false;
		}
	}
}
