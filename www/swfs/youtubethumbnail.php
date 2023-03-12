<?php 
header('Content-Type: image/png');

if(file_exists("youtubeimg/".$_GET['videoid'].".png"))
{
	$image = imagecreatefrompng("youtubeimg/".$_GET['videoid'].".png");
	imagepng($image);
	imagedestroy($image);
	exit();
}

$data = file_get_contents("http://i1.ytimg.com/vi/".$_GET['videoid']."/default.jpg");

$image = imagecreatefromstring($data);

if ($image == null) {
	exit("error 1");
}

function createThumbnail($sourceImage, $desiredWidth = 120, $desiredHeight = 90) {
	$width  = imagesx($sourceImage);
	$height = imagesy($sourceImage);

	$virtualImage = imagecreatetruecolor($desiredWidth, $desiredHeight);
	imagecopyresampled($virtualImage, $sourceImage, 0, 0, 0, 0, $desiredWidth, $desiredHeight, $width, $height);
	
	return $virtualImage;
}

 function convertPNGto8bitPNG($sourcePath) {
    $srcimage = imagecreatefrompng($sourcePath);
    list($width, $height) = getimagesize($sourcePath);
    $img = imagecreatetruecolor($width, $height);
    $bga = imagecolorallocatealpha($img, 0, 0, 0, 127);
    imagecolortransparent($img, $bga);
    imagefill($img, 0, 0, $bga);
    imagecopy($img, $srcimage, 0, 0, 0, 0, $width, $height);
    imagetruecolortopalette($img, false, 255);
    imagesavealpha($img, true);
    imagepng($img, $sourcePath);
    imagedestroy($img);
    }

$UniqueId = $_GET['videoid'];

$img = $image;

imagepng($img, "youtubeimg/" . $UniqueId . '.png', 9);
convertPNGto8bitPNG("youtubeimg/" . $UniqueId . '.png');

imagepng($img);
imagedestroy($im);