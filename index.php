<?php 
include_once('Pages/Classes.php');
include_once('simplehtmldom/simple_html_dom.php');

$ebay = Curl::app('https://www.ebay.com')
 ->headers(1)
 ->referer('google.com')
 ->ssl(0)
 ->add_header('accept:application/json; charset=utf-8; profile="https://www.mediawiki.org/wiki/Specs/Summary/1.2.0"')
 ->add_header('accept-language:ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7')
 ->random_agent()
 ->cookie('ebay');
 $ebay->config_save('wiki.cfg');
$html = $ebay->request('/sch/Kitchen-Dining-Bar-/20625/i.html?_pgn=2&_ipg=200');





 ?>