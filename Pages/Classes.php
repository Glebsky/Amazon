<?php 
/**
* Curl Class
*/
class Curl
{
	private $ch;
	private $host;
	private $options;
	public $headers = array('Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
			'Accept-Encoding: gzip, deflate',
			'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3');

	public static function app($host){
		return new self($host);
	}


	private function __construct($host)
	{
		$this->ch = curl_init();
		$this->host = $host;
		$this->options = array(CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => array());
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->headers);
		$this->random_agent();
	}

	public function __destruct(){
		curl_close($this->ch);
	}
	/**
	 * Creates headers
	 * @param True or False
	 * @return Curl Class
	 * @author 
	 **/
	public function headers($act)
	{
		$this->set(CURLOPT_HEADER,$act);
		return $this;
	}

	public function set($name, $value){
		$this->options[$name] = $value;
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

	public function add_header($header)
	{
		$this->options[CURLOPT_HTTPHEADER][] = $header;
		$this->set(CURLOPT_HTTPHEADER, $this->options[CURLOPT_HTTPHEADER]);
		return $this;
	}

	public function add_headers($headers)
	{
		foreach ($headers as $h)
			$this->options[CURLOPT_HTTPHEADER][] = $h;

		$this->set(CURLOPT_HTTPHEADER, $this->options[CURLOPT_HTTPHEADER]);
		return $this;
	}

	public function clear_headers()
	{
		$this->options[CURLOPT_HTTPHEADER] = array();
		$this->set(CURLOPT_HTTPHEADER,$this->options[CURLOPT_HTTPHEADER]);
		return $this;
	}

	public function follow($param)
	{
		$this->set(CURLOPT_FOLLOWLOCATION,$param);
		return $this;
	}

	public function referer($param)
	{
		$this->set(CURLOPT_REFERER,$param);
		return $this;
	}

	public function user_agent($agent)
	{
		$this->set(CURLOPT_USERAGENT,$agent);
		return $this;
	}

	public function config_load($file)
	{
		$data = file_get_contents($file);
		$data = unserialize($data);
		curl_setopt_array($this->ch, $data);

		foreach ($data as $key => $value) {
			$this->options[$key] = $value;
		}
		return $this;
	}

	public function config_save($file)
	{
		$data = serialize($this->options);
		file_put_contents($file, $data);
		return $this;
	}

	public function random_userAgent($agent)
	{
		
	}

	private function process_result($data)
	{
		if (isset($this->options[CURLOPT_HEADER]) && $this->options[CURLOPT_HEADER]) {

			$info = curl_getinfo($this->ch);

			$headers_part = trim(substr($data, 0, $info['header_size']));
			$body_part = substr($data, $info['header_size']);

			$headers_part = str_replace("\r\n", "\n", $headers_part);
			$headers = str_replace("\r", "\n", $headers_part);

			$headers = explode("\n\n", $headers);
			$headers_part = end($headers);

			$lines = explode("\n", $headers_part);
			$headers = array();

			$headers['start'] = $lines[0];

			for ($i=1; $i < count($lines); $i++) { 
				$del_pos = strpos($lines[$i], ':');
				$name = substr($lines[$i], 0, $del_pos);
				$value = substr($lines[$i], $del_pos + 2);
				$headers[$name] = $value;
			}

			return array(
			'headers' => $headers,
			'html' => $body_part
		);
		}else{
			return array(
			'headers' => array(),
			'html' => $data
		);
		}

	}

	public function ssl($act=1)
	{
		try {
		$this->set(CURLOPT_SSL_VERIFYPEER, $act);
		$this->set(CURLOPT_SSL_VERIFYPEER, $act);
		return $this;
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	public function post($arr)
	{	try {
		if ($arr === false) {
			$this->set(CURLOPT_POST,false);
			return $this;
		}
		$this->set(CURLOPT_POST, true);
		$this->set(CURLOPT_POSTFIELDS, http_build_query($arr));
		return $this;
	} catch (Exception $e) {
		die($e->getMessage());
	}
	}

	public function cookie($cookie_name)
	{
		try {
		$this->set(CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/cookie/'.$cookie_name.'.cki');
		$this->set(CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].'/cookie/'.$cookie_name.'.cki');
		return $this;
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	public function random_agent(){
		// https://udger.com/resources/ua-list - больше агентов тут
		$data = file("config/agents.txt");
		$this->user_agent(trim($data[rand(0, count($data) - 1)]));
		return $this;
	}
}
 ?>