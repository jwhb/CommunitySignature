<?php

require_once 'vendor/autoload.php';
require_once 'base.signature.php';

function kick_disabled($service){
	die(print("The service '$service' is unsupported/disabled."));
}

if(@$_GET['error']){
	
	print('<pre>' . print_r($_GET, true) . '</pre>');
	
}else{
	
	$tokens = explode('?', $_SERVER["REQUEST_URI"]); //Remove GET arguments
	$tokens = explode('/', $tokens[0]);
	
	$service = $tokens[sizeof($tokens) - 2] or $service = '';
	$username = $tokens[sizeof($tokens) - 1] or $username = 'JWhy';
	
	
	switch(strtolower($service)){
		
		case 'github':
			require_once 'github.config.php';
			if(Config::$enabled){
				
				require_once('github.signature.php');
				$generator = new GitHubSignature();
				$generator->showSignature($username);
	
				break;
			}
		
		case 'soundcloud':
			
			require_once 'soundcloud.config.php';
			if(Config::$enabled){
				
				require_once('soundcloud.signature.php');
				$generator = new SoundCloudSignature();
				$generator->showSignature($username);
	
				break;
			}
		
		default:
			kick_disabled($service);
			break;
			
	}
}
