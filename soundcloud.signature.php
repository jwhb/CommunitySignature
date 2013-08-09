<?php

class SoundCloudSignature extends SignatureGenerator {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function getUserTracks($userid){
		$sc_cfg = Config::getSoundCloudConfig();
		$usertracks = json_decode(
			file_get_contents(str_replace(
				array('{user}', '{client_id}'),
				array($userid, $sc_cfg['api_clientid']),
				$sc_cfg['api_url']['user_tracks']
			))
		);
		return($usertracks);
	}
	
	public function getUserInfo($userid){
		$sc_cfg = Config::getSoundCloudConfig();
		$user = json_decode(
			file_get_contents(str_replace(
				array('{user}', '{client_id}'),
				array($userid, $sc_cfg['api_clientid']),
				$sc_cfg['api_url']['user_info']
			))
		);
		return($user);
	}
	
	public function getPopularItems($items, $limit = 3, $quick_info = true){
		
		function compareRepos($a, $b){
		    if ($a == $b)
		    	return 0;
		    return ($a->playback_count < $b->playback_count);
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
					'name' => $rp->title,
					'stars' => $rp->playback_count,
					'lang' => '',
				);
			}
		}
		
		return($top_items);
	}
	
	public function showSignature($username) {
		$sc_cfg = Config::getSoundCloudConfig();
		try {
			$user = $this->getUserInfo($username);
			$songs = $this->getUserTracks($user->id);
			$avatar_url = $user->avatar_url;
			
			$this->img_gen->generateSignature(array(
				'avatar_url' => $avatar_url,
				'username' => $user->username,
				'items' => $this->getPopularItems($songs),
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
