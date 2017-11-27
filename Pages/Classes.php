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

	public function __destruct(){
		curl_close($this->ch);
	}
	public function set($name, $value){
		curl_setopt($this->ch, $name, $value);
		return $this;
	}
	public function request($url){
		curl_setopt($this->ch, CURLOPT_URL, $this->make_url($url));
		$data = curl_exec($this->ch);
		return $this->process_result($data);
	}
	private function make_url($url){
		if ($url[0] != '/') {
			$url = '/'. $url;
		}
		return $this->host.$url;
	}

	public function process_result($data)
	{
		$p_n = "\n";
		$p_rn = "\r\n";

		$h_end_n = strpos($data, $p_n.$p_n);
		$h_end_rn =  strpos($data, $p_rn.$p_rn);

		$start = $h_end_n;
		$p = $p_n;
		if ($h_end_n === false || $h_end_rn < $h_end_n) {
			$start = $h_end_rn;
			$p = $p_rn;
		}

		$headers_part = substr($data, 0, $start);
		$body_part = substr($data, $start + strlen($p)*2);
		$lines = explode($p, $headers_part);

		$headers = array();

		$headers['start'] = $lines[0];

		for ($i=1; $i < count($lines); $i++) { 
			$del_pos = strpos($lines[$i], ':');
			$name = substr($lines[$i], 0,$del_pos);
			$value = substr($lines[$i], $del_pos+2);
			$headers[$name] = $value;
		}

		return array(
			'headers' => $headers,
			'html' => $body_part
		);

	}

	public function ssl($act)
	{
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $act);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $act);
		return $this;
	}
}
 ?>