<?php

require_once 'vendor/autoload.php';
require_once 'config.php';
require_once 'ImgGenerator.php';

class GithubSignature {
	private $img_gen;
	
	public function __construct() {
		$this->img_gen = new ImgGenerator ();
	}
	
	public function showSignature($username) {
		try {
			$client = new Github\Client(new Github\HttpClient\CachedHttpClient(array(
				'cache_dir' => '/tmp/github-api-cache' 
			)));
			
			$repositories = $client->api('user')->show($username);
			
			$this->img_gen->generateByText(print_r($repositories, true));
		}catch(Exception $e){
			$this->img_gen->generateByText($e->getMessage());
		}
	}
	
}
