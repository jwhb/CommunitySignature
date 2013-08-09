<?php


class GitHubSignature extends SignatureGenerator{

	public function __construct() {
		parent::__construct();
	}

	public function getAvatarUri($avatar_id, $avatar_size){
		$url = str_replace(
				array('{id}', '{size}'),
				array($avatar_id, $avatar_size),
				Config::getImageConfig()['avatar']['url']
		);
		return($url);
	}	
	
	public function getPopularItems($items, $limit = 3, $quick_info = true){
		
		function compareRepos($a, $b){
		    if ($a == $b)
		    	return 0;
		    return ($a['watchers'] < $b['watchers']);
		}
		
		usort($items, 'compareRepos');
		
		$top_items = array();
		for($i = 0; $i < $limit; $i++){
			
			if(!$quick_info){
				//don't put the information in form, just return the whole object
				$top_items[] = $items[$i];
			} else {
				//filter the information, "quick info mode"
				$rp = $items[$i];
				
				$top_items[] = array(
					'name' => $rp['name'],
					'stars' => $rp['watchers'],
					'lang' => $rp['language'],
				);
			}
		}
		
		return($top_items);
	}
	
	public function showSignature($username) {
		try {
			$client = new Github\Client(new Github\HttpClient\CachedHttpClient(array(
				'cache_dir' => '/tmp/github-api-cache' 
			)));
			
			$user = $client->api('user')->show($username);
			$items = $client->api('user')->repositories($username);
			$avatar_url = $this->getAvatarUri($user['gravatar_id'],
				Config::getImageConfig()['avatar']['size']);
			
			$this->img_gen->generateSignature(array(
				'avatar_url' => $avatar_url,
				'username' => $user['login'],
				'items' => $this->getPopularItems($items),
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
