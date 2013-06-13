<?php

require_once 'vendor/autoload.php';
require_once 'config.php';
require_once 'ImgGenerator.php';

class GithubSignature {
	private $img_gen;
	
	public function __construct() {
		$this->img_gen = new ImgGenerator ();
	}
	
	private function getGravatarUri($gravatar_id, $gravatar_size){
		$url = str_replace(
			array('{id}', '{size}'), 
			array($gravatar_id, $gravatar_size),
			Config::getImageConfig()['gravatar_url']
		);
		return($url);
	}
	
	public function showSignature($username) {
		try {
			$client = new Github\Client(new Github\HttpClient\CachedHttpClient(array(
				'cache_dir' => '/tmp/github-api-cache' 
			)));
			
			$user = $client->api('user')->show($username);
			
			$info = array(
				'gravatar_url' => $this->getGravatarUri($user['gravatar_id'],
					Config::getImageConfig()['gravatar_size'])
			);
			$this->img_gen->generateSignature($info);
		}catch(Exception $e){
			$this->img_gen->generateByText($e->getMessage());
		}
	}
	
}
