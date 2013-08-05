<?php

require_once 'vendor/autoload.php';
require_once 'ImgGenerator.php';

function kick_disabled($service){
	die(print("The service '$service' is unsupported/disabled."));
}

$tokens = explode('?', $_SERVER["REQUEST_URI"]); //Remove GET arguments
$tokens = explode('/', $tokens[sizeof($tokens)-1]);

$service = $tokens[sizeof($tokens) - 2] or $service = '';
$username = $tokens[sizeof($tokens) - 1] or $username = 'JWhy';

switch(strtolower($service)){
	
	case 'github':
		require_once 'github.config.php';
		if(Config::$enabled){
			
			require_once('githubsignature.php');
			$github = new GithubSignature();
			$github->showSignature($username);
			
		}else{
			kick_disabled('GitHub');
		}
		break;
		
	default:
		kick_disabled($service);
		break;
		
}
