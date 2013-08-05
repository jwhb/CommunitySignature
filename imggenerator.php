<?php

class ImgGenerator {
	
	private $config;
	
	public function __construct(){
		$this->config = Config::getImageConfig();
	}
	
	public function generateByText($text){
		$ffile = $this->config['fontfile'];
		$fsize = $this->config['elements']['error']['fontsize'];
		
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
		$cfg = $this->config;
		$ffile = $cfg['fontfile'];
		$gsize = $cfg['gravatar']['size'];

		$width = $cfg['img_width'];
		$heigth = $cfg['img_heigth'];
		
		$colors = $cfg['col'];
		
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
		$offsetX = + $cfg['gravatar']['offsetX'];
		$offsetY = + $cfg['gravatar']['offsetY'];
		imagecopy($im, $avatar, 0 + $offsetX, 0 + $offsetY, 0, 0, imagesx($avatar), imagesy($avatar));
		
		//Add username as header to image
		$usr = $cfg['elements']['username'];
		$header_y = $usr['fontsize'] + $usr['offsetY'];
		imagettftext($im, $usr['fontsize'], 0, $gsize + $usr['offsetX'], $usr['fontsize'] + $usr['offsetY'], $black, $ffile, $info['username']);
		unset($usr, $avatar);

		//Add repos to image
		
		$repocfg = $cfg['elements']['repos'];
		$starcfg = $cfg['elements']['stars'];
		$rfsize = $repocfg['fontsize'];
		$rowY[0] = $header_y + $rfsize + $repocfg['offsetY'];
		$max_repo_width = 0;
		$max_startxt_width = 0;
		
		//Add repo names
		foreach($info['repos'] as $num=>$repo){
			//Write row positions
			if($num > 0) $rowY[$num] = $rowY[$num - 1] + $rfsize + $repocfg['offsetY'];
			$reponame = $info['repos'][$num]['name'];
			
			imagettftext($im, $rfsize, 0, $gsize + $repocfg['offsetX'], $rowY[$num], $black, $ffile, $reponame);
			
			//Determine max width for table style indention
			$box = imagettfbbox($rfsize, 0, $ffile, $reponame);
			if($box[4] > $max_repo_width) $max_repo_width = $box[4];
		}
		
		//Add repo star counts
		foreach($info['repos'] as $num=>$repo){
			//Add repo star count
			$stars = $info['repos'][$num]['stars'];
			imagettftext($im, $rfsize, 0, $gsize + $max_repo_width + $starcfg['text_offsetX'], $rowY[$num], $black, $ffile, $stars);
			
			//Determine max width for table style indention
			$box = imagettfbbox($rfsize, 0, $ffile, $stars);
			if($box[4] > $max_startxt_width) $max_startxt_width = $box[4];
		}
		
		//Add repo star images
		$stars_cfg = $this->config['elements']['stars'];
		$star_im = imagecreatefrompng($stars_cfg['img_file']);
		$start_x = $gsize + $max_repo_width + $starcfg['text_offsetX'] + $max_startxt_width + $stars_cfg['img_offsetX'];
		foreach($info['repos'] as $num=>$repo){
			imagecopy($im, $star_im, $start_x, $rowY[$num] + $stars_cfg['img_offsetY'], 0, 0, imagesx($star_im), imagesy($star_im));
		}
		
		unset($repocfg, $rfsize, $rowY);
		
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
