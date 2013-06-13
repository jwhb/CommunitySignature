<?php 

class Config{
	
	public static function getGithubConfig(){
		return(array());
	}
	
	public static function getImageConfig(){
		return(array(
			'fontsize' => 10,
			'fontfile' => './opensans.ttf',
			'gravatar_url' => 'http://www.gravatar.com/avatar/{id}?s={size}',
			'gravatar_size' => 100,
		));
	}

}