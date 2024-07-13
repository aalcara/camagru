<!DOCTYPE html>
<html lang="en">

<body>
	<h2>Login</h2>

	<?php if (isset($data['errorMsg'])): ?>
		<div class="error-msg">
			<?php echo $data['errorMsg']; ?>
		</div>
	<?php endif; ?>
	<form action="/auth/register" method="POST">
		<label for="username">Username:</label>
		<input type="text" id="username" name="username" required>

		<label for="email">Email:</label>
		<input type="email" id="email" name="email" required>

		<label for="password">Password:</label>
		<input type="password" id="password" name="password" required>

		<button type="submit">Register</button>
	</form>
</body>


</html>