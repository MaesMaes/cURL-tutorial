<pre>
<?

require_once 'vendor/autoload.php';
require_once 'functions.php';

define(RWURL, 'http://randewoo.ru/');
define(W_RANDS, 'tmp/woomen_brands.dat');
define(M_RANDS, 'tmp/men_brands.dat');

// Выкачиваем все ссылки

// $html = file_get_contents( 'http://randewoo.ru/brands?product_category_id=27' );
// $woomen_brands = phpQuery::newDocument( $html );
// $brands = pq( '.brands__list' )->find('a');
//
// $wman = array();
//
// foreach ($brands as $brand) {
//     $brand = pq( $brand );
//     $wman[] = array(
//         'link' => $brand->attr('href'),
//         'title' => $brand->text()
//     );
// }
// $q = json_encode($wman);
// file_put_contents('tmp/men_brands.dat', $q);



// Формируем JSON с нужными сслыками

// $brands = json_decode(file_get_contents( 'tmp/brands.txt' ));
// $w_brands = json_decode(file_get_contents( M_RANDS ));
//
// $w_brand_titles = array();
// foreach ($w_brands as $w_brand) {
//     $w_brand_titles[] = $w_brand->title;
// }
// // xprint($brands);
//
// $w_brand_needle = array();
// $i = 0;
// foreach ($w_brands as $w_brand) {
//     if(in_array($w_brand->title, $brands)) {
//         $w_brand_needle[] = array(
//             'link' => $w_brand->link,
//             'title' => $w_brand->title
//         );
//     }
//
// }



// Формируем JSON с нужными c конкретным брендом

// $brands_array = array();
// $brands = json_decode(file_get_contents( 'tmp\woomen_brands_needle.json' ));
// foreach ($brands as $brand) {
//     $brand_list = file_get_contents( RWURL . $brand->link );
//     $list = phpQuery::newDocument( $brand_list );
//     $items = pq( 'li.products__item' )->find('.products__title a');
//     $list_array = array();
//     foreach ($items as $item) {
//         $item = pq( $item );
//         $list_array[] = array(
//             'link' => $item->attr('href'),
//             'title' => $item->text()
//         );
//     }
//     $brands_array[$brand->title] = $list_array;
//     sleep(3);
//     xprint($list_array);
// }
// file_put_contents('tmp/woomen_items_list.json', json_encode($brands_array));
// xprint($brands_array);

// $i = 0;
// $t = json_decode(file_get_contents( 'tmp/woomen_items_list.json' ));
// foreach ($t as $key => $brand) {
//
//     $brand_list = array();
//     foreach ($brand as &$item) {
//         // Информация о товаре
//         $item_source = array();
//
//         $dom = file_get_contents( RWURL . $item->link );
//         $html = phpQuery::newDocument( $dom );
//
//         // Наименование и стоимость экземпляров
//         $types_arr = array();
//         $types = pq('article.product')->find('div[itemprop="offers"]');
//         foreach ($types as $type) {
//             $type = pq( $type );
//             $types_arr[] = array(
//                 'title' => $type->find('meta[itemprop="name"]')->attr('content'),
//                 'price' => (int)$type->find('meta[itemprop="price"]')->attr('content'),
//                 'volume' => preg_replace("/[^0-9]/", '', $type->find('meta[itemprop="name"]')->attr('content'))
//             );
//         }
//
//         $item_source[] = array(
//             'img' => pq( '.slider__slide img' )->attr('src'),
//             'types' => $types_arr,
//             'desc' => pq('.product__tabs')->find('.collapsable')->html(),
//             'dl' => pq('.product__tabs')->find('.dl')->html(),
//             'brand' => $key
//         );
//
//         $brand_list[] = $item_source;
//
//     }
//     $i++;
//     if($i > 2) break;
//
//     file_put_contents("tmp/woomen_brands/woomen.json", json_encode($brand_list) . "\n", FILE_APPEND);
//     sleep(1);
// }

$t = file( 'tmp/woomen_brands/woomen.json' );
foreach ($t as $brand) {
    xprint(json_decode($brand));
}

























//
