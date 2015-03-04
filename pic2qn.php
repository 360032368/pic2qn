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

	public function get2send($url){

		$file_info = $this->remote2local($url);
		if(count($file_info)==0) return array();

		$file = $file_info['full'];
		$qn_key = str_replace($this->local, '', $file);

		$file_real_path = $file;//dirname(__FILE__).str_replace('./', '/', $file);
		$result = $this->local2qn($qn_key,$file_real_path);

		$file_info['key'] = $qn_key;
		//print_r($result);
		if($result){
			return $file_info;
		}else{
			return array();
		}

	}

	public function remote2local($url){
		$file = $this->create_file_path($url);
		$this->curl->reutersload($url,$file['full']);
		if(file_exists($file['full'])) return $file;
		else return array();
	}

	public function local2qn($key,$file){

		Qiniu_SetKeys($this->accessKey, $this->secretKey);
		$putPolicy = new Qiniu_RS_PutPolicy($this->bucket);
		$upToken = $putPolicy->Token(null);
		$putExtra = new Qiniu_PutExtra();
		$putExtra->Crc32 = 1;
		list($ret, $err) = Qiniu_PutFile($upToken, $key, $file, $putExtra);
		//var_dump($err);
		return ($err == null);

	}

	private function create_file_path($url = 'png'){
		$time = time();

		$path = $this->local;
		if(!is_dir($path)){
			mkdir($path);
		}

		$path.= date('Ymd',$time).'/';
		if(!is_dir($path)){
			mkdir($path);
		}

		$name = md5($time);

		$arr_path = parse_url($url);
		$str_path = explode('.',$arr_path['path']);
		$ext = $str_path[count($str_path)-1];

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

	public function remove2qn($url){
		$tmp = parse_url($url);
		$path = $tmp['path'];
		if( $path[0] === '/' ) $path = substr($path, 1);

		Qiniu_SetKeys($this->accessKey, $this->secretKey);
		$client = new Qiniu_MacHttpClient(null);

		$err = Qiniu_RS_Delete($client,$this->bucket,$path);
		return ($err == null);
	}

}