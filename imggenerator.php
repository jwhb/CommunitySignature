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
	
	public function generateSignature($info){
		$ffile = $this->config['fontfile'];
		$fsize = $this->config['fontsize'];
		
		$width = $this->config['img_width'];
		$heigth = $this->config['img_heigth'];
		
		$colors = $this->config['col'];
		
		$back_col = $colors['background'];
		
		//Define avatar and destination signature images
		$avatar = imagecreatefromjpeg($info['gravatar_url']);
		$im = @imagecreatetruecolor($width, $heigth) or die ('Could not create image');
		
		//Allocate colors
		$black = imagecolorallocate($im, 0, 0, 0);
		$white = imagecolorallocate($im, 255, 255, 255);
		
		//Fill background
		$bkgcol = $colors['background'];
		$bkgcol = imagecolorallocate($im, $bkgcol[0], $bkgcol[1], $bkgcol[2]);
		imagefilledrectangle($im, 0, 0, $width, $heigth, $bkgcol);
		unset($bkgcol);
		
		//Copy avatar image into destination image
		imagecopy($im, $avatar, 0, 0, 0, 0, imagesx($avatar), imagesy($avatar));
		
		
		imagettftext($im, $fsize, 0, 10, 10, $black, $ffile, 'HALLO');
		
		//Pass complete image
		$this->showImage($im);
	}
	
	private function showImage($image){
		if(!isset($_GET['raw'])) header('Content-type: image/png');
		imagepng($image);
		imagedestroy($image);
		exit();
	}
	
}
