<?php


class GithubSignature {
	private $img_gen;
	
	public function __construct() {
		$this->img_gen = new ImgGenerator();
	}
	
	private function getavatarUri($avatar_id, $avatar_size){
		$url = str_replace(
			array('{id}', '{size}'), 
			array($avatar_id, $avatar_size),
			Config::getImageConfig()['avatar']['url']
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
	}
	
	public function showSignature($username) {
		try {
			$client = new Github\Client(new Github\HttpClient\CachedHttpClient(array(
				'cache_dir' => '/tmp/github-api-cache' 
			)));
			
			$user = $client->api('user')->show($username);
			$repos = $client->api('user')->repositories($username);
			$avatar_url = $this->getavatarUri($user['gravatar_id'],
				Config::getImageConfig()['avatar']['size']);
			
			$this->img_gen->generateSignature(array(
				'avatar_url' => $avatar_url,
				'username' => $user['login'],
				'items' => $this->getPopularUserRepos($repos),
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
