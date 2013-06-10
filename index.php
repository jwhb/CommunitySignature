<?php

$fontfile = './colab.ttf';
$fontsize = 10;

$nums = '';
for($i = 0; $i < 10; $i++){
	$nums .= rand();
}

$quote = "So many numbers " . $nums;
$bounds = imagettfbbox($fontsize, 0, $fontfile, $quote);
$width = $bounds[4] - $bounds[6];
$height = $bounds[3] - $bounds[5];
$space = 5;
$image = imagecreatetruecolor($width + $space, $height + $space);
$colors['bg'] = imagecolorallocate($image, 255, 255, 255);
$colors['fg'] = imagecolorallocate($image, 51, 51, 51);
imagefilledrectangle($image, 0, 0, $width + $space, $height + $space, $colors['bg']);
$x = $space;
$y = $fontsize + $space;
imagettftext($image, $fontsize, 0, $x, $y, $colors['fg'], $fontfile, $quote);
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);