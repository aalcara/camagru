<?php

function image_hash_to_path($hash)
{
	$image_hash_hex = bin2hex($hash);

	$file_path = "/var/www/html/public/uploads/{$image_hash_hex}.png";
	if (!file_exists($file_path)) {
		echo "File does not exist";
		return;
	}

	return str_replace("/var/www/html/public", "", $file_path);
}
