<?php
function error($code) {
    $message = array(
        "404.1" => "Ошибка подключения к базе данных",
        "404.2" => "Метод не поддерживается"
    );

    throw new Exception($message[$code]);
}
?>