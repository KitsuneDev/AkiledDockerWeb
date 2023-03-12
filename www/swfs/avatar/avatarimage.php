<?php
$inputFigure = strtolower($_GET["figure"]);
$inputAction = isset($_GET["action"]) ? strtolower($_GET["action"]) : 'std';
$inputDirection = isset($_GET["direction"]) ? (int) $_GET["direction"] : 2;
$inputHeadDirection = isset($_GET["head_direction"]) ? (int) $_GET["head_direction"] : $inputDirection;
$inputGesture = isset($_GET["gesture"]) ? strtolower($_GET["gesture"]) : 'std';
$inputSize = isset($_GET["size"]) ? strtolower($_GET["size"]) : 'n';
$inputFormat = isset($_GET["img_format"]) ? strtolower($_GET["img_format"]) : 'gif';
$inputFrame = isset($_GET["frame"]) ? strtolower($_GET["frame"]) : '0';
$inputHeadOnly = isset($_GET["headonly"]) ? (bool) $_GET["headonly"] : false;
$inputTrim = isset($_GET["trim"]) ? (bool) $_GET["trim"] : false;

$inputAction = explode(",", $inputAction);
$inputFormat = $inputFormat == "gif" ? "gif" : "png";
$inputFrame = explode(",", $inputFrame);

$expandedstyle = $inputFigure . ".s-" . $inputSize . ($inputHeadOnly ? "h" : "") . ".g-" . $inputGesture . ".d-" . $inputDirection . ".h-" . $inputHeadDirection . ".a-" . implode("-", str_replace("=", "", $inputAction)) . ".f-" . implode("-", str_replace("=", "", $inputFrame)) . ($inputTrim ? "t" : "");

require_once dirname(__FILE__) . '/class.avatarimage.php';

$avatarImage = new AvatarImage($inputFigure, $inputDirection, $inputHeadDirection, $inputAction, $inputGesture, $inputFrame, $inputHeadOnly, $inputSize, $inputTrim);
$image = $avatarImage->Generate($inputFormat);

if ($image !== false) {
    header('Process-Time: ' . $avatarImage->processTime);
    header('Error-Message: ' . $avatarImage->error);
    header('Debug-Message: ' . $avatarImage->debug);
    header('Generator-Version: ' . $avatarImage->version);
    header('Content-Type: image/' . $inputFormat);
    echo $image;
}
