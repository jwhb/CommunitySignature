<?php

require_once('githubsignature.php');

$tokens = explode('/', $_SERVER["REQUEST_URI"]); //Get URI segments
$tokens = explode('?', $tokens[sizeof($tokens)-1]); //Remove GET arguments
$username = $tokens[0] or $username = 'JWhy';

$github = new GithubSignature();
$github->showSignature($username);
