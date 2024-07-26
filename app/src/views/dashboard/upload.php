<h1>
	This is your art
</h1>
<?php if (isset($data['image'])): ?>
	<div>
	<img src="<?php echo image_hash_to_path($data['image']['hash']); ?>" alt="Captured Image">
	</div>
<?php endif; ?>