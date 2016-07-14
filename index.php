<?php

require_once 'vendor/autoload.php';
require_once 'functions.php';

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

// xprint( $html );


// ---------------------------------------------------------------------
// --[ cURL POST ]------------------------------------------------------
// ---------------------------------------------------------------------

$cookie_file = realpath( 'tmp/cookie.txt' );

function request( $url, $postdata = null, $cookiefile = 'tmp/c', $proxy = true ) {



    $ch = curl_init( $url );

    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    // Притворяемся браузером
    curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:47.0) Gecko/20100101 Firefox/47.0' );

    curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie_file );
    curl_setopt( $ch, CURLOPT_COOKIEFILE, $cookie_file );

    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

    if ( $proxy ) {

        // Будем использовать прокси
        curl_setopt( $ch, CURLOPT_PROXY, '118.161.73.188:8888' );
        // Указываем тип прокси: CURLPROXY_HTTP, CURLPROXY_SOCKS4
        curl_setopt( $ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP );

        /**
         * Устанавливаем timout-ы. Указываем время после которого
         * будет считаться что запрос не дошел.
         */
        // Timeout на загрузку данных, сек.
        curl_setopt( $ch, CURLOPT_TIMEOUT, 9 );
        // Время в течение которого соединение должно быть установлено, сек.
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 6 );

    }

    if ( $postdata ) {
        /**
         * Прикрепляем POST поля
         * Принимает либо массив ключ-значение или строку GET
         */
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postdata );
    }

    $html = curl_exec( $ch );
    curl_close( $ch );

    return $html;
}

// Очищаем файл
file_put_contents( 'tmp/cookie.txt', '' );

$post = array(
    'op' => 'login',
    'dest' => 'https//www.reddit.com/',
    'user' => 'jScheq',
    'passwd' => '',
    'api_type' => 'json'
);

$html = request( 'https://www.reddit.com/post/login', $post );

//echo $html;


// ---------------------------------------------------------------------
// --[ parse XML ]------------------------------------------------------
// ---------------------------------------------------------------------

$blog = simplexml_load_file("http://b2blogger.com/pressroom/priority-63680/rss.xml");

// xd( $blog );


// ---------------------------------------------------------------------
// --[ cURL Работа с прокси ]-------------------------------------------
// ---------------------------------------------------------------------

/**
 *  Виды прокси:
 *      1. Прозрачные - Виден ваш ip и виден proxy ip. Большинство из них это кэшерующие proxy.
 *                      Предназначены для Инронета. Используются для фильтрации трафика. Легко
 *                      палятся сканерами.
 *      2. Анонимные  - Не передают ваш ip. Палит сам факт использования proxy: при отправке запроса
 *                      на сервер добавляет заголовок о использовании proxy.
 *      3. Элитные    - Скрывают ваш ip и факт использования прокси просто передавая запрос.
 *  Проблемы proxy:
 *      1. Timeout
 *      2. Анонимность
 *      3. Валидация ответа
 *      4. "Дохлость" прокси
 *  Протоколы proxy:
 *      1.  HTTP, HTTPS
 *      2.  socks4/5
 */

file_put_contents( 'tmp/cookie.txt', '' );

$html = cURL_request( 'http://httpbin.org/ip' );



//
