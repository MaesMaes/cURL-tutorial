<?php

// Подключаем автозагрузчик composer
require_once 'vendor/autoload.php';
require_once 'simple_html_dom.php';

$cookie_file = realpath( 'tmp/cookie.txt' );

function cURL_request( $url, $postdata = null, $cookiefile = 'tmp/c', $proxy = true )
{

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

/**
 * Получение html таблицы проркси с сайта hidemyass
 */
function get_proxy_hidemyass()
{
    // Читает содержимое файла в строку
    $html = file_get_contents( 'http://proxylist.hidemyass.com/search-1305531#listable' );

    // Создаем объект pq из строки
    $proxylist = phpQuery::newDocument( $html );

    // Получаем таблицу proxy
    $proxytable = pq( 'table#listable tbody tr' );

    $proxy = array();

    foreach ($proxytable as $tr) {

        // Переводим DOMElement Object в phpQueryObject Object
        $tr = pq( $tr );

        $ip = $tr->find('td:nth-child(2)');
        // $ip = str_get_html( $ip )->plaintext;


        // Ищем классы прячущие мусор
        $none_display = array();
        $style = $ip->find('style');
        $style = explode('.', $style);
        foreach ($style as $value) {
            $chunk = explode( '{', $value );
            $class = $chunk[0];
            if ( strpos( $chunk[1], 'none' ) ) {
                array_push($none_display, $class);
            }
        }

        // Удаляем мусор
        foreach ($ip as $el) {
            $el = pq( $el );
            $el->find('*[style="display:none"]')->remove();
            foreach ($none_display as $class)
                $el->find("*[class=$class]")->remove();
        }

        $ip = str_get_html( $ip )->plaintext;
        $ip = str_replace( ' ', '', $ip );

        $port = str_replace( ' ', '', $tr->find('td:nth-child(3)') );
        $type = str_replace( ' ', '', $tr->find('td:nth-child(7)') );

        array_push($proxy, array(
            'ip'   => $ip,
            'port' => $port,
            'type' => $type
        ));

    }

    // Очищаем помять от документа $proxylist
    phpQuery::unloadDocuments( $proxylist );

    return $proxy;
}
















//
