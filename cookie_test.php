<?php

// Подключаем автозагрузчик composer
require_once 'vendor/autoload.php';

$cook = false;

if ( isset( $_COOKIE[ 'curl_session_cookie' ] ) ) {
    $cook = true;
    echo "Сессионная кука есть\r\n";
}

if ( isset( $_COOKIE[ 'curl_normal_cookie' ] ) ) {
    $cook = true;
    echo "Нормальная кука\r\n";
}

setcookie( 'curl_session_cookie', 1 );
/**
 * Укажем время истечения cookie как microtime(Возвращает текущую метку времени Unix
 * с микросекундами в формате float() из-за переданного параметра true)
 */
setcookie( 'curl_normal_cookie', 1, microtime( true ) + 10000 );

if ( $cook ) {
    echo 'Я тебя знаю!';
} else {
    echo 'Вы новенький';
}
