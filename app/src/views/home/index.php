<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Home</title>
</head>

<body>
	<h1>Bem-vindo ao Camagru!</h1>

	<nav>
		<a href="/home">Home</a>
		<a href="/about">About Us</a>
	</nav>

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
</body>

</html>