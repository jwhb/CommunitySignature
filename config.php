<?php 

class Config{
	
	public static function getGithubConfig(){
		return(array());
	}
	
	public static function getImageConfig(){

		$white = array(255, 255, 255);
		$black = array(0, 0, 0);
		
		return(array(
			
			/* Gravatar settings */
			'gravatar' => array(
				'url' => 'http://www.gravatar.com/avatar/{id}?s={size}',
				'size' => 80,
				'offsetX' => 0,
				'offsetY' => 3,
			),
				
			/* Signature font settings */
			'fontfile' => './assets/opensans.ttf',
			
			/* Image dimensions */
			'img_width' => 285,
			'img_heigth' => 86,
				
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
				'repos' => array(
					'fontsize' => 10,
					'offsetX' => 3,
					'offsetY' => 8,
				),
				'stars' => array(
					'img_file' => './assets/star.png',
					'text_offsetX' => 15,
					'img_offsetX' => 3,
					'img_offsetY' => -15,
				),
				'error' => array(
					'fontsize' => 10,
				)
			),
		));
	}

}