<?php
function connect() {
	try {
		$pdo = new PDO("mysql:host=localhost;dbname=uhealth;charset=utf8", 'root', '');
	} catch (PDOException $e) {
		error("404.1");
	}
	return $pdo;
}

function error($code) {
	$message = array(
		"404.1" => "Ошибка подключения к базе данных",
		"404.2" => "Метод не поддерживается"
	);

	throw new Exception($message[$code]);
}
?>