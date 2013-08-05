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
			Config::getImageConfig()['gravatar']['url']
		);
		return($url);
	}
	
	public function getPopularUserRepos($userrepos, $limit = 3, $quick_info = true){
		
		function compareRepos($a, $b){
		    if ($a == $b)
		    	return 0;
		    return ($a['watchers'] < $b['watchers']);
		}
		
		usort($userrepos, 'compareRepos');
		
		$top_repos = array();
		for($i = 0; $i < $limit; $i++){
			
			if(!$quick_info){
				//don't put the information in form, just return the whole object
				$top_repos[] = $userrepos[$i];
			} else {
				//filter the information, "quick info mode"
				$rp = $userrepos[$i];
				
				$top_repos[] = array(
					'name' => $rp['name'],
					'stars' => $rp['watchers'],
					'lang' => $rp['language'],
				);
			}
		}
		
		return($top_repos);
		
		die();
		return array(
				array(
						'name' => 'CraftButler', 'stars' => 1, 'lang' => 'Java'
				),
				array(
						'name' => 'ElectEx', 'stars' => 2, 'lang' => 'PHP'
				),
				array(
						'name' => 'ChattyKitten', 'stars' => 4, 'lang' => 'Java'
				),
		);
	}
	
	public function showSignature($username) {
		try {
			$client = new Github\Client(new Github\HttpClient\CachedHttpClient(array(
				'cache_dir' => '/tmp/github-api-cache' 
			)));
			
			$user = $client->api('user')->show($username);
			$repos = $client->api('user')->repositories($username);
			$gravatar_url = $this->getGravatarUri($user['gravatar_id'],
				Config::getImageConfig()['gravatar']['size']);
			
			$this->img_gen->generateSignature(array(
				'gravatar_url' => $gravatar_url,
				'username' => $user['login'],
				'repos' => $this->getPopularUserRepos($repos),
			));
		}catch(Exception $e){
			if(isset($_GET['raw'])) print($e . "<br><br>\n\n");
			
			$msg = 'An error occured.';
			if($e->getMessage() == 'Not Found'){
				$msg = "The username '$username' could not be found";
			}else{
				$msg = $e->getMessage();
			}
			$this->img_gen->generateByText($msg);
		}
	}
	
}
