<?php

require_once('inc/curl.php');
require_once('qiniu/io.php');
require_once('qiniu/rs.php');

Class Pic2qn {

	private $bucket = '';
	private $accessKey = '';
	private $secretKey = '';
	private $local = './images/';
	private $curl;

	public function __construct($bucket,$accessKey,$secretKey){
		$this->bucket = $bucket;
		$this->accessKey = $accessKey;
		$this->secretKey = $secretKey;

		$this->curl = new Curl();
	}

	public function config($params = array()){
		if (count($params) > 0){
			foreach ($params as $key => $val){
				if (isset($this->$key)){
					$this->$key = $val;
				}
			}
		}
	}

	public function remote2local($url){
		//$this->$curl->reutersload()
		//echo 1;
		$file = $this->create_file_path($url);
		$this->curl->reutersload($url,$file['full']);
		if(file_exists($file['full'])) return $file;
		else return null;
	}

	private function create_file_path($url = 'png'){
		$time = time();

		$path = $this->local;
		if(!is_dir($path)){
			mkdir($path);
		}

		$path.= date('Ym',$time).'/';
		if(!is_dir($path)){
			mkdir($path);
		}

		$name = md5($time);

		$str = explode('.',parse_url($url)['path']); 
		$ext = $str[count($str)-1];

		$file = $name.'.'.$ext;

		$full = $path.$file;

		return array(
				'path' => $path,
				'name' => $name,
				'ext' => $ext,
				'file' => $file,
				'full' => $full
			);

	}

}