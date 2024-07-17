<h1>
	This is your art
</h1>
<?php if (isset($data['image'])): ?>
	<div>
	<img src="<?php echo $data['image']; ?>" alt="Captured Image">
	</div>
<?php endif; ?>