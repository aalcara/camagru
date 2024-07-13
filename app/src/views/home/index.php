
<h1>Bem-vindo ao Camagru!</h1>

<h1>
	<?php if (isset($data['users']) && is_array($data['users'])): ?>
		<ul>
			<?php foreach ($data['users'] as $user): ?>
				<li><?php echo htmlspecialchars($user['username']); ?></li>
			<?php endforeach; ?>
		</ul>
	<?php else: ?>
		<p>No user data.</p>
	<?php endif; ?>
</h1>

