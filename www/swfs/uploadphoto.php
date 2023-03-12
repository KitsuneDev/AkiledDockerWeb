<?php 
define ('MAX_DATA_LEN',   409000);
define ('PIC_DEF_WIDTH',  320);
define ('PIC_DEF_HEIGHT', 320);

define ('PIC_TMB_WIDTH',  100);
define ('PIC_TMB_HEIGHT', 100);

if ($_SERVER['REQUEST_METHOD'] != 'POST' || $_SERVER['CONTENT_TYPE'] != 'application/octet-stream' 
	|| !strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) || $_SERVER['CONTENT_LENGTH'] == 0 || empty($_SERVER['HTTP_USER_AGENT']))
	exit("-1");

$image = file_get_contents('php://input', false, null, 0, MAX_DATA_LEN);

// if (empty($compressedInput)) {
	// exit("Image null 1");
// }

// $image = @zlib_decode($compressedInput);

if ($image == null) {
	exit("-2");
}

$img_origine = imagecreatefromstring($image);

if ($img_origine === false || imagesx($img_origine) != PIC_DEF_WIDTH || imagesy($img_origine) != PIC_DEF_HEIGHT) {
	exit("-3");
}

function createThumbnail($sourceImage, $desiredWidth = 100, $desiredHeight = 100) {
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

$UniqueId = md5(uniqid(rand(), true));

$img = ($img_origine);

imagepng($img, "newfoto/photos/" . $UniqueId . '.png', 9);
imagepng(createThumbnail($img, PIC_TMB_WIDTH, PIC_TMB_HEIGHT), "newfoto/photos/" . $UniqueId . '_small.png', 9);

convertPNGto8bitPNG("newfoto/photos/" . $UniqueId . '.png');
convertPNGto8bitPNG("newfoto/photos/" . $UniqueId . '_small.png');

imagedestroy($img);
exit($UniqueId);