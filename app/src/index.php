<?php

$mysqli = new mysqli($_ENV['MYSQL_HOST'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD'], $_ENV['MYSQL_DB']);

if ($mysqli->connect_error) {
    die("Erro de conexão com o MySQL: " . $mysqli->connect_error);
}

echo "Conexão com o MySQL estabelecida com sucesso!";
?>