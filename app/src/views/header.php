<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Camagru</title>
	<!-- TODO: css -->
    <!-- <link rel="stylesheet" href="/path/to/your/css/style.css"> -->
</head>
<body>
    <header>
        <h1>Camagru</h1>
        <nav>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/gallery">Gallery</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="/dashboard">Dashboard</a></li>
                    <li><a href="/auth/logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="/auth/login">Login</a></li>
                    <li><a href="/auth/signup">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>