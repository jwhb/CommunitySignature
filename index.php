<?php

require_once('githubsignature.php');

$tokens = explode('/', $_SERVER["REQUEST_URI"]);
$username = $tokens[sizeof($tokens)-1] or $username = 'JWhy';

$github = new GithubSignature();
$github->showSignature($username);
