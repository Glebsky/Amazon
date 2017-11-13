<?php 
include_once('Pages/Classes.php');


$c = Curl::app('https://www.ebay.com/')->set(CURLOPT_HEADER, 1);
$html = $c->request('/');
echo $html;

 ?>