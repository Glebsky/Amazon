<?php 
/**
* Curl Class
*/
class Curl
{
	private $ch;
	private $host;

	public static function app($host){
		return new self($host);
	}


	private function __construct($host)
	{
		$this->ch = curl_init();
		$this->host = $host;
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
	}

	private function __destruct(){
		curl_close($this->ch);
	}
	public function set($name, $value){
		curl_setopt($this->ch, $name, $value);
		return $this;
	}
	public function request($url){
		curl_setopt($this->ch, CURLOPT_URL, $this->make_url($url));
		$data = curl_exec($this->ch);
		return $data;
	}
	private function make_url($url){
		if ($url[0] != '/') {
			$url = '/'. $url;
		}
		return $this->host.$url;
	}
}
 ?>