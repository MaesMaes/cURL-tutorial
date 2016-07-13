<?php

require_once 'vendor/autoload.php';

// ---------------------------------------------------------------------
// --[ phpQuery ]-------------------------------------------------------
// ---------------------------------------------------------------------

// Читает содержимое файла в строку
$html = file_get_contents( 'https://pogoda.yandex.ru/ufa/' );

// Создаем объект pq из строки
$yandex = phpQuery::newDocument( $html );

// Получаем текст
$temperature = pq( '.current-weather__thermometer_type_now' )->text();

// Получаем атрибут
$wind = pq( '.current-weather__info-row > abbr.icon-abbr' )->attr('title');

// Получим список
$forecast = pq( 'ul.forecast-brief' )->children( 'li.forecast-brief__item:not(.forecast-brief__item_gap)' );

foreach ($forecast as $li) {
    /**
     * Изначально в $li лежит DOMElement Object, после обертки
     * в pq() там будет phpQueryObject Object
     */
    // Переводим DOMElement Object в phpQueryObject Object
    $li = pq( $li );

    // Удаляем все иконки
    $li->find( '.icon' )->remove();

    //xprint(  $li->html() );
}

// Очищаем помять от документа $yandex
phpQuery::unloadDocuments( $temperature );


// ---------------------------------------------------------------------
// --[ cURL ]-----------------------------------------------------------
// ---------------------------------------------------------------------

// Получаем дескриптор потока ресурса (url handle)
$ch = curl_init( 'http://ya.ru' );

/**
 * Устанавливаем опции. Св-ва это константы.
 * Данные полученные в результате запроса будут сохраняться в переменные
 */
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

// Получить заголовки (HEADER)
// curl_setopt( $ch, CURLOPT_HEADER, true );

// Не получать тело (например, нам нужны только заголовки)
// curl_setopt( $ch, CURLOPT_NOBODY, true );

// Следуем за редиректом если он есть
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );

// Отключаем проверки для загрузки страниц по HTTPS без боли
curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
// Остановки cURL от проверки сертификата узла сети.
curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );


// Выполняем запрос
$html = curl_exec( $ch );

// Закрываем дексриптор
curl_close( $ch );


// ---------------------------------------------------------------------
// --[ cURL cookies ]---------------------------------------------------
// ---------------------------------------------------------------------

// realpath возвращает канонизированный абсолютный путь к файлу
$cookie_file = realpath( 'tmp/cookie.txt' );

$ch = curl_init( 'http://curl-tutorial/cookie_test.php' );

curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch, CURLOPT_HEADER, true );

// Устанавливаем куку curl_normal_cookie и curl_session_cookie в 1
// curl_setopt( $ch, CURLOPT_COOKIE, 'curl_normal_cookie=1; curl_session_cookie=1;' );

/**
 * $cookiefile - Имя файла, в котором будут сохранены все внутренние cookies
 * текущей передачи после закрытия дескриптора, например, после вызова curl_close.
 * Используются для имитации пользователя. Т.е. сохраняет все cookie в файл
 * после чего ими можно пользоваться при отправки запросов.
 */
// Записываем cookie в файл
curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie_file );
// Читаем из файла и передаем cookie обратно
curl_setopt( $ch, CURLOPT_COOKIEFILE, $cookie_file );
// Запрещает передачу сессионным cookie. Будут передоваться только обычные.
// curl_setopt( $ch, CURLOPT_COOKIESESSION, true );

$html = curl_exec( $ch );

curl_close( $ch );

xprint( $html );






//
