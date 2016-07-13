<?php

require_once 'vendor/autoload.php';

// ---------------------------------------------------------------------
// --[ Main code ]------------------------------------------------------
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

    xprint(  $li->html() );
}

// Очищаем помять от документа $yandex
phpQuery::unloadDocuments( $temperature );

xprint( $forecast );
//xd( $wind, 'Отладочная информация' );
