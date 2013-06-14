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
			//'gravatar_url' => 'http://www.gravatar.com/avatar/{id}?s={size}',
			'gravatar_url' => 'http://localhost/assets/jwhy.jpg',
			'gravatar_size' => 100,
				
			/* Signature font settings */
			'fontsize' => 10,
			'fontfile' => './opensans.ttf',
				
			/* Image dimensions */
			'img_width' => 400,
			'img_heigth' => 100,
				
			/* Colors */
			'col' => array(
				'background' => $white
			),
		));
	}

}