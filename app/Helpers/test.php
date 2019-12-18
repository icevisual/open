<?php



// Create the image
$im = imagecreatetruecolor(400, 30);

// Create some colors
$white = imagecolorallocate($im, 255, 255, 255);
$grey = imagecolorallocate($im, 128, 128, 128);
$black = imagecolorallocate($im, 0, 0, 0);
imagefilledrectangle($im, 0, 0, 399, 29, $white);

// The text to draw
$text = 'Testing...';
// Replace path by your own font path
$font = '/home/icevisual/Projects/PHP7/smell_open/app/Extensions/Verify/fonts/1.ttf';

// Add some shadow to the text
imagettftext($im, 20, 0, 11, 21, $grey, $font, $text);

// Add the text
imagettftext($im, 20, 0, 10, 20, $black, $font, $text);

// Using imagepng() results in clearer text compared with imagejpeg()
imagepng($im,"fff.png");
imagedestroy($im);
