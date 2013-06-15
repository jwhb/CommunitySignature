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
			$gravatar_url = $this->getGravatarUri($user['gravatar_id'],
				Config::getImageConfig()['gravatar_size']);
			
			$this->img_gen->generateSignature(array(
				'gravatar_url' => $gravatar_url,
				'username' => $user['name'],
				'repos' => array(
					array(
						'name' => 'CraftButler', 'stars' => 1, 'lang' => 'Java' 
					),
					array(
						'name' => 'ElectEx', 'stars' => 2, 'lang' => 'PHP' 
					),
					array(
						'name' => 'ChattyKitten', 'stars' => 4, 'lang' => 'Java' 
					),
				)
			));
		}catch(Exception $e){
			if(isset($_GET['raw'])) print($e . "<br><br>\n\n");
			$this->img_gen->generateByText($e->getMessage());
		}
	}
	
}
