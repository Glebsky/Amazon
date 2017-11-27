<?php 
include_once('Pages/Classes.php');

$post = array('login' => 'admin',
	'pass' => '123456');

$c = Curl::app('https://www.ebay.com')->set(CURLOPT_HEADER, 1)
 ->set(CURLOPT_POST,true)
 ->set(CURLOPT_POSTFIELDS,http_build_query($post))
 ->set(CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/1.txt')
 ->set(CURLOPT_COOKIEFILE,$_SERVER['DOCUMENT_ROOT'].'/1.txt')
 ->ssl(0);
$html = $c->request('/');
var_dump($html);

 ?>