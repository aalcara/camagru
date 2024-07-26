<?php
class Image extends Model
{

	public function createImage($owner_id, $hash)
	{
		$image_id = uniqid("", true);

		$stmt = $this->db->prepare("INSERT INTO images (id, owner_id, hash) VALUES (?, ?, UNHEX(?))");
		$stmt->bind_param('sss', $image_id, $owner_id, $hash);

		if ($stmt->execute()) {
			return $image_id;
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

	public function getPaginatedImages($limit = 10, $page = 1)
	{
		$offset = ($page - 1) * $limit;

		$query = "
            SELECT images.*, users.username AS owner
            FROM images
            JOIN users ON images.owner_id = users.id
            ORDER BY images.created_at DESC
            LIMIT ? OFFSET ?
        ";
		$stmt = $this->db->prepare($query);
		if (!$stmt) {
			die("Prepare failed: " . $this->db->error);
		}

		$stmt->bind_param("ii", $limit, $offset);
		$stmt->execute();

		$result = $stmt->get_result();
		$images = $result->fetch_all(MYSQLI_ASSOC);

		$stmt->close();
		return $images;
	}

	public function getTotalImages()
	{
		$query = "SELECT COUNT(*) as total FROM images";
		$result = $this->db->query($query);
		$row = $result->fetch_assoc();
		return $row['total'];
	}
}
