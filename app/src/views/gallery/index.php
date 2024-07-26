<h1>Gallery</h1>
<div class="gallery">
	<?php if (!empty($data['images'])): ?>
		<?php foreach ($data['images'] as $image): ?>
			<div class="gallery-item">
				<img src="<?php echo image_hash_to_path($image['hash']) ?>" alt="Image">
				<p>
					<?php echo $image['owner'] ?>
					<?php echo htmlspecialchars($image['created_at']) ?>

				</p>
			</div>
		<?php endforeach; ?>

		<?php for ($i = 1; $i <= $data['total_pages']; $i++): ?>
			<a href="/gallery?page=<?php echo $i; ?>">
				<?php echo $i; ?>
			</a>
		<?php endfor; ?>
	<?php else: ?>
		<p>No images found.</p>
	<?php endif; ?>

</div>