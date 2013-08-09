<?php 

class Config{
	
	public static $enabled = true;
	
	public static function getSoundCloudConfig(){
		return(array(
			'api_clientid' => 'insert_here',
		//	'api_clientsecret' => '',
			'api_url' => array(
				'user_info' => 'http://api.soundcloud.com/users/{user}.json?client_id={client_id}',
				'user_tracks' => 'http://api.soundcloud.com/users/{user}/tracks.json?client_id={client_id}'
			)
		));
	}
	
	public static function getImageConfig(){

		$white = array(255, 255, 255);
		$black = array(0, 0, 0);
		
		return(array(
			
			/* avatar settings */
			'avatar' => array(
				'size' => 80,
				'offsetX' => 0,
				'offsetY' => 0,
			),
				
			/* Signature font settings */
			'fontfile' => './assets/opensans.ttf',
			
			/* Image dimensions */
			'img_width' => 285,
			'img_heigth' => 80,
				
			/* Colors */
			'col' => array(
				'background' => $white,
			),
				
			/* Elements */
			'elements' => array(
				'username' => array(
					'fontsize' => 18,
					'offsetX' => 3,
					'offsetY' => 5,
				),
				'items' => array(
					'fontsize' => 10,
					'offsetX' => 3,
					'offsetY' => 8,
				),
				'stars' => array(
					'img_file' => './assets/star.png',
					'text_offsetX' => 11,
					'img_offsetX' => 3,
					'img_offsetY' => -12,
				),
				'error' => array(
					'fontsize' => 10,
				)
			),
		));
	}

}