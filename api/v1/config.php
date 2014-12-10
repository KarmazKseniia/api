<?php
function connect() {
	try {
		$pdo = new PDO("mysql:host=localhost;dbname=healthylifestyle", 'root', '');
	} catch (PDOException $e) {
		error("404.1", "Ошибка подключения к базе данных");
		die();
	}
	return $pdo;
}

function error($code, $message) {
	echo json_encode(
		array( 
			"error" => array(
				"code" => $code,
				"message" => $message
			)
		)
	);
}
?>