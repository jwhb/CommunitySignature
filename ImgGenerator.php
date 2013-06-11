<?php

class ImgGenerator {
	
	private $config;
	
	public function __construct(){
		$this->config = Config::getImageConfig();
	}
	
	public function generateByText($text){
		$ffile = $this->config['fontfile'];
		$fsize = $this->config['fontsize'];
		
		$bounds = imagettfbbox($fsize, 0, $ffile, $text);
		$width = $bounds[4] - $bounds[6];
		$height = $bounds[3] - $bounds[5];
		$space = 5;
		$image = imagecreatetruecolor($width + $space, $height + $space);
		$colors['bg'] = imagecolorallocate($image, 255, 255, 255);
		$colors['fg'] = imagecolorallocate($image, 51, 51, 51);
		imagefilledrectangle($image, 0, 0, $width + $space, $height + $space, $colors['bg']);
		$x = $space;
		$y = $fsize + $space;
		imagettftext($image, $fsize, 0, $x, $y, $colors['fg'], $ffile, $text);
		
		$this->showImage($image);
	}
	
	public function showImage($image){
		header('Content-type: image/png');
		imagepng($image);
		imagedestroy($image);
		exit();
	}
	
}
