<?php

abstract class SignatureGenerator {

	protected $img_gen;

	public function __construct() {
		require_once('ImgGenerator.php');
		$this->img_gen = new ImgGenerator();
	}
	
}
