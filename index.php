<?php 
include_once('Pages/Classes.php');
include_once('simplehtmldom/simple_html_dom.php');

$c = Curl::app('https://www.ebay.com')
 ->headers(1)
 ->referer('google.com')
 ->ssl(0)
 ->add_header('accept:application/json; charset=utf-8; profile="https://www.mediawiki.org/wiki/Specs/Summary/1.2.0"')
 ->add_header('accept-language:ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7')
 ->random_agent()
 ->cookie('ebay');
 $c->config_save('wiki.cfg');
$html = $c->request('/sch/Kitchen-Dining-Bar-/20625/i.html?_pgn=2&_ipg=200');
echo $html['html'];
$pHtml = str_get_html($html['html']);
$title = $pHtml->find('.lvtitle');

foreach ($title as $t) {
	echo $t->plaintext.'<br>';
}

$pHtml->clear();
unset($pHtml);


 ?>