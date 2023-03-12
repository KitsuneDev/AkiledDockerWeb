<?php
//
//    Habbo standalone avatar image generator
//    version 1.2.8 / May.10 2015
//
//    Use example: http://labs.habox.org/generator-avatar
//
//    This program is free software; you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation; either version 2 of the License, or
//    (at your option) any later version.
//
//    Copyright 2015 T-Racing Development / coded by Tsuka
//    http://www.t-racing.org
//

ini_set("display_errors", 0);

define("LITE_RECOLOR_FUNCTION", false);
// Use function fast processing at color subtraction.
// processing speed improves, but there is little difference from the real color.

define("PATH_RESOURCE", dirname(__FILE__) . "/resource/");

define('IMAGE_CANVAS_RESIZE_LEFT', 0);
define('IMAGE_CANVAS_RESIZE_TOP', 1);
define('IMAGE_CANVAS_RESIZE_RIGHT', 2);
define('IMAGE_CANVAS_RESIZE_BOTTOM', 3);

class AvatarImage
{
    public $version = "Avatar-retro";
    public $error = null;
    public $debug = null;
    public $settings = null;

    public $format = "png";
    public $figure = array();
    public $direction = 0;
    public $headDirection = 0;
    public $action = array("std"); //std, sit, lay, wlk, wav, sit-wav, swm
    public $gesture = "std"; //std, agr, sml, sad, srp, spk, eyb
    public $frame = array(0);
    public $isLarge = false;
    public $isSmall = false;
    public $isHeadOnly = false;
    public $Trim = false;
    public $rectWidth = 64;
    public $rectHeight = 110;
    public $HiddenType = array();

    public $handItem = false;
    public $drawAction = array(
        "body" => "std",
        "wlk" => false,
        "sit" => false,
        "gesture" => false,
        "eye" => false,
        "speak" => false,
        "itemRight" => false,
        "handRight" => false,
        "handLeft" => false,
        "swm" => false,
    );
    public $drawOrder = "std";
    public $TypeFigure = array("li", "lh", "ri", "bd", "sh", "lg", "ch", "wa", "ca", "rh", "rs", "hd", "fc", "ey", "hr", "hrb", "fa", "ea", "ha", "he", "lc", "cc", "cp", "rc");

    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        if (!(error_reporting() & $errno)) {
            return;
        }

        $errorType = array(
            E_ERROR => 'ERROR',
            E_WARNING => 'WARNING',
            E_PARSE => 'PARSING ERROR',
            E_NOTICE => 'NOTICE',
            E_CORE_ERROR => 'CORE ERROR',
            E_CORE_WARNING => 'CORE WARNING',
            E_COMPILE_ERROR => 'COMPILE ERROR',
            E_COMPILE_WARNING => 'COMPILE WARNING',
            E_USER_ERROR => 'USER ERROR',
            E_USER_WARNING => 'USER WARNING',
            E_USER_NOTICE => 'USER NOTICE',
            E_STRICT => 'STRICT NOTICE',
            E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR',
        );

        switch ($errno) {
            case E_ERROR:
            case E_WARNING:
                $this->error .= $errorType[$errno] . ": " . $errstr . " / Fatal error on line " . $errline . " in file " . $errfile . "\n";
                break;

            default:
                break;
        }

        return true;
    }

    public function __construct($figure, $direction, $headDirection, $action, $gesture, $frame, $isHeadOnly, $scale, $trim)
    {
        set_error_handler(array($this, "errorHandler"));

        $time_start = microtime(true);

        $this->settings = array(
            "map" => $this->getJSON(PATH_RESOURCE . "map.json"),
            "figuredata" => $this->getJSON(PATH_RESOURCE . "figuredata.json"),
            "partsets" => $this->getJSON(PATH_RESOURCE . "partsets.json"),
            "draworder" => $this->getJSON(PATH_RESOURCE . "draworder.json"),
            "offset" => array(),
        );

        $this->direction = $this->validateDirection($direction) ? $direction : 0;
        $this->headDirection = $this->validateDirection($headDirection) ? $headDirection : 0;

        switch ($scale) {
            case "l":
                $this->isLarge = true;
                break;
            case "s":
                $this->isSmall = true;
                break;
            case "n":
            default:
                break;
        }
        if ($isHeadOnly) {
            $this->isHeadOnly = true;
        }

        if ($trim) {
            $this->Trim = true;
        }

        if (!empty($figure)) {
            $parts = explode('.', $figure);
            if (count($parts) == 0) {
                $time_end = microtime(true);
                $this->processTime = $time_end - $time_start;
                return false;
            }

            foreach ($parts as $value) {
                $data = explode("-", $value);
                if (array_key_exists($data[0], $this->figure) || !in_array($data[0], $this->TypeFigure)) {
                    continue;
                }

                if ($data[0] == "hd" && ($data[1] == 999999 || $data[1] == 99999)) {
                    $this->figure[$data[0]] = array("type" => $data[0], "id" => 185, "color" => array((int) $data[2], (int) $data[3]));
                    $gesture = "noface";
                } else {
                    $this->figure[$data[0]] = array("type" => $data[0], "id" => $data[1], "color" => array((int) $data[2], (int) $data[3]));
                }

            }

            if (!array_key_exists("hd", $this->figure) && !$this->Trim) {
                $this->figure["hd"] = array("type" => "hd", "id" => "180", "color" => array(0, 0));
            }

            if (!array_key_exists("lg", $this->figure) && !$this->Trim) {
                $this->figure["lg"] = array("type" => "lg", "id" => "270", "color" => array(0, 0));
            }

            $frame = is_array($frame) ? $frame : array($frame);
            foreach ($frame as $value) {
                $_frame = explode("=", $value);
                $_action = $_frame[0] != "" ? $_frame[0] : "def";
                @$this->frame[$_action] = (int) $_frame[1];
            }

            $this->gesture = $gesture;
            switch ($this->gesture) {
                case "spk":
                    $this->drawAction['speak'] = $this->gesture;
                    break;
                case "eyb":
                    $this->drawAction['eye'] = $this->gesture;
                    break;
                case "":
                    $this->drawAction['gesture'] = "std";
                    break;
                default:
                    $this->drawAction['gesture'] = $this->gesture;
                    break;
            }
            $this->action = is_array($action) ? $action : array($action);
            foreach ($this->action as $value) {
                $_action = explode("=", $value);
                switch ($_action[0]) {
                    case "wlk":
                    case "sit":
                        $this->drawAction[$_action[0]] = $_action[0];
                        break;
                    case "lay":
                        $this->drawAction['body'] = $_action[0];
                        $this->drawAction['eye'] = $_action[0];
                        list($this->rectWidth, $this->rectHeight) = array($this->rectHeight, $this->rectWidth);
                        switch ($this->gesture) {
                            case "spk":
                                $this->drawAction['speak'] = "lsp";
                                $this->frame["lsp"] = $this->frame[$this->gesture];
                                break;
                            case "eyb":
                                $this->drawAction['eye'] = "ley";
                                break;
                            case "std":
                                $this->drawAction['gesture'] = $_action[0];
                                break;
                            default:
                                $this->drawAction['gesture'] = "l" . substr($this->gesture, 0, 2);
                                break;
                        }
                        break;
                    case "wav":
                        $this->drawAction['handLeft'] = $_action[0];
                        break;
                    case "crr":
                    case "drk":
                        $this->drawAction['handRight'] = $_action[0];
                        $this->drawAction['itemRight'] = $_action[0];
                        $this->handItem = (int) $_action[1];
                        break;
                    case "swm":
                        $this->drawAction[$_action[0]] = $_action[0];
                        if ($this->gesture == "spk") {
                            $this->drawAction['speak'] = "sws";
                        }
                        break;
                    case "":
                        $this->drawAction['body'] = "std";
                        break;
                    default:
                        $this->drawAction['body'] = "std"; //$_action[0];
                        break;
                }
            }

            if ($this->drawAction['sit'] == "sit") {
                if ($this->direction != 0 && $this->direction != 2 && $this->direction != 4 && $this->direction != 6) {
                    $this->direction = 4;
                }

                $this->drawOrder = "sit";
                if ($this->drawAction['handRight'] == "drk" && $this->direction >= 2 && $this->direction <= 3) {
                    $this->drawOrder .= ".rh-up";
                } elseif ($this->drawAction['handLeft'] && $this->direction == 4) {
                    $this->drawOrder .= ".lh-up";
                }
            } elseif ($this->drawAction['body'] == "lay") {
                $this->drawOrder = "lay";
                if ($this->direction != 2 && $this->direction != 4) {
                    $this->direction = 4;
                }

                $this->headDirection = $this->direction;
                $this->isHeadOnly = false;
            } elseif ($this->drawAction['handRight'] == "drk" && $this->direction >= 0 && $this->direction <= 3) {
                $this->drawOrder = "rh-up";
            } elseif ($this->drawAction['handLeft'] && $this->direction >= 4 && $this->direction <= 6) {
                $this->drawOrder = "lh-up";
            }
        } else {
            $this->action = $action;
        }

        $time_end = microtime(true);
        $this->processTime = $time_end - $time_start;

        return true;
    }

    public function getJSON($filename)
    {
        return json_decode(file_get_contents($filename), true);
    }
    public function HEX2RGB($hex)
    {
        $rgb = array();
        for ($x = 0; $x < 3; $x++) {
            $rgb[$x] = hexdec(substr($hex, (2 * $x), 2));
        }
        return $rgb;
    }
    public function validateDirection($direction)
    {
        return (is_numeric($direction) && $direction >= 0 && $direction <= 7);
    }

    public function Generate($format = "png")
    {
        $time_start = microtime(true);

        $avatarImage = imageCreateTrueColor($this->rectWidth, $this->rectHeight);
        imageAlphaBlending($avatarImage, false);
        imageSaveAlpha($avatarImage, true);
        $rectMask = imageColorAllocateAlpha($avatarImage, 0, 0, 0, 127);
        imageFill($avatarImage, 0, 0, $rectMask);

        $activeParts['rect'] = $this->getActivePartSet($this->isHeadOnly ? "head" : "figure", true);
        $activeParts['head'] = $this->getActivePartSet("head");
        $activeParts['eye'] = $this->getActivePartSet("eye");
        $activeParts['gesture'] = $this->getActivePartSet("gesture");
        $activeParts['speak'] = $this->getActivePartSet("speak");
        $activeParts['walk'] = $this->getActivePartSet("walk");
        $activeParts['sit'] = $this->getActivePartSet("sit");
        $activeParts['itemRight'] = $this->getActivePartSet("itemRight");
        $activeParts['handRight'] = $this->getActivePartSet("handRight");
        $activeParts['handLeft'] = $this->getActivePartSet("handLeft");
        $activeParts['swim'] = $this->getActivePartSet("swim");

        $drawParts = $this->getDrawOrder($this->drawOrder, $this->direction);
        if ($drawParts === false) {
            $drawParts = $this->getDrawOrder("std", $this->direction);
        }

        $setParts = array();
        foreach ($this->figure as $partSet) {
            $ret = $this->getPartColor($partSet['type'], $partSet['id'], $partSet['color']);
            if (empty($ret) && $partSet['type'] == "hd") {
                $ret = $this->getPartColor($partSet['type'], "180", $partSet['color']);
            }

            if (empty($ret) && $partSet['type'] == "lg") {
                $ret = $this->getPartColor($partSet['type'], "270", $partSet['color']);
            }

            if (empty($ret) && $partSet['type'] == "ch") {
                $ret = $this->getPartColor($partSet['type'], "630", $partSet['color']);
            }

            $setParts = array_merge_recursive($setParts, $ret);
        }

        if ($this->handItem !== false) {
            $setParts["ri"][0] = array("id" => $this->handItem,
                "colorable" => "",
                "color" => "");
        }

        imageAlphaBlending($avatarImage, true);

        $drawCount = 0;
        foreach ($drawParts as $id => $type) {
            if (!array_key_exists($type, $setParts)) {
                continue;
            }

            foreach ($setParts[$type] as $drawPart) {
                $uniqueName = "";

                $uniqueName = $this->getPartUniqueName($type, $drawPart['id']);

                if ($uniqueName == "") {
                    continue;
                }

                if (array_key_exists($type, $this->HiddenType)) {
                    continue;
                }
                if (!is_array($drawPart)) {
                    continue;
                }
                if ($this->isHeadOnly && !$activeParts['rect'][$type]['active']) {
                    continue;
                }

                $drawDirection = $this->direction;
                $drawAction = false;
                if ($activeParts['rect'][$type]['active']) {
                    $drawAction = $this->drawAction['body'];
                }
                if ($activeParts['head'][$type]['active']) {
                    $drawDirection = $this->headDirection;
                }
                if ($activeParts['speak'][$type]['active'] && $this->drawAction['speak']) {
                    $drawAction = $this->drawAction["speak"];
                }
                if ($activeParts['gesture'][$type]['active'] && $this->drawAction['gesture'] == "noface") {
                    continue;
                }
                if ($activeParts['gesture'][$type]['active'] && $this->drawAction['gesture']) {
                    $drawAction = $this->drawAction['gesture'];
                }
                if ($activeParts['eye'][$type]['active']) {
                    $drawPart['colorable'] = false;
                    if ($this->drawAction['eye']) {
                        $drawAction = $this->drawAction['eye'];
                    }
                }
                if ($activeParts['walk'][$type]['active'] && $this->drawAction['wlk']) {
                    $drawAction = $this->drawAction['wlk'];
                }
                if ($activeParts['sit'][$type]['active'] && $this->drawAction['sit']) {
                    $drawAction = $this->drawAction['sit'];
                }
                if ($activeParts['handRight'][$type]['active'] && $this->drawAction['handRight']) {
                    $drawAction = $this->drawAction['handRight'];
                }
                if ($activeParts['itemRight'][$type]['active'] && $this->drawAction['itemRight']) {
                    $drawAction = $this->drawAction['itemRight'];
                }
                if ($activeParts['handLeft'][$type]['active'] && $this->drawAction['handLeft']) {
                    $drawAction = $this->drawAction['handLeft'];
                }
                if ($activeParts['swim'][$type]['active'] && $this->drawAction['swm']) {
                    $drawAction = $this->drawAction['swm'];
                }

                if (!$drawAction) {
                    continue;
                }

                $drawPartRect = $this->getPartResource(
                    $uniqueName,
                    $drawAction,
                    $type,
                    $drawPart['id'],
                    $drawDirection
                );

                $drawCount++;

                if ($drawPartRect === false) {
                    $this->debug .= "PART[" . $drawAction . "][" . $type . "][" . $drawPart['id'] . "][" . $drawDirection . "][" . $this->getFrameNumber($type, $drawAction, @(int) $this->frame[$drawAction]) . "]/";
                    continue;
                } else {
                    $this->debug .= $drawPartRect['lib'] . ":" . $drawPartRect['name'] . "(" . $drawPartRect['width'] . "x" . $drawPartRect['height'] . ":" . $drawPartRect['offset']['x'] . "," . $drawPartRect['offset']['y'] . ")/";
                }

                $drawPartRectTransparentColor = imageColorTransparent($drawPartRect['resource']);
                if ($drawPart['colorable']) {
                    $this->setPartColor($drawPartRect['resource'], $drawPart['color']);
                }

                $_posX = -$drawPartRect['offset']['x'] + ($this->drawAction['body'] == "lay" ? ($this->rectWidth / 2.4) : 0);
                $_posY = ($this->rectHeight / 2) - $drawPartRect['offset']['y'] + ($this->drawAction['body'] == "lay" ? ($this->rectHeight / 3.2) : ($this->rectHeight / 2.4));
                if ($drawPartRect['isFlip']) {
                    $_posX = -($_posX + $drawPartRect['width'] - ($this->rectWidth + 1));
                }

                imageCopy($avatarImage, $drawPartRect['resource'], $_posX, $_posY, 0, 0, $drawPartRect['width'], $drawPartRect['height']);

                imageDestroy($drawPartRect['resource']);
            }
        }
        $this->debug .= "DRAWCOUNT: " . $drawCount;

        if ($this->isHeadOnly) {
            $temp = imageCreateTrueColor(54, 62);
            imageAlphaBlending($temp, false);
            $rectMask = imageColorAllocateAlpha($temp, 0, 0, 0, 127);
            imageSaveAlpha($temp, true);
            $x = imagecopyresampled($temp, $avatarImage, -6, -8, 0, 0, $this->rectWidth, $this->rectHeight, $this->rectWidth, $this->rectHeight);
            if ($x) {
                $avatarImage = $temp;
            }
            $this->rectWidth = 54;
            $this->rectHeight = 62;
        }
        if ($this->isSmall) {
            $temp = imageCreateTrueColor($this->rectWidth / 2, $this->rectHeight / 2);
            imageAlphaBlending($temp, false);
            $rectMask = imageColorAllocateAlpha($temp, 0, 0, 0, 127);
            imageSaveAlpha($temp, true);
            $x = imagecopyresampled($temp, $avatarImage, 0, 0, 0, 0, $this->rectWidth / 2, $this->rectHeight / 2, $this->rectWidth, $this->rectHeight);
            if ($x) {
                $avatarImage = $temp;
            }
        }
        if ($this->isLarge) {
            $temp = imageCreateTrueColor($this->rectWidth * 2, $this->rectHeight * 2);
            imageAlphaBlending($temp, false);
            $rectMask = imageColorAllocateAlpha($temp, 0, 0, 0, 127);
            imageSaveAlpha($temp, true);
            $x = imagecopyresampled($temp, $avatarImage, 0, 0, 0, 0, $this->rectWidth * 2, $this->rectHeight * 2, $this->rectWidth, $this->rectHeight);
            if ($x) {
                $avatarImage = $temp;
            }
        }
        if ($this->Trim) {
            $this->ImageTrim($avatarImage, imagecolorallocatealpha($avatarImage, 0, 0, 0, 127), null);
        }

        ob_start();
        if ($format == "gif") {
            $this->format = "gif";
            $rectMask = imageColorAllocateAlpha($avatarImage, 0, 0, 0, 127);
            imageColorTransparent($avatarImage, $rectMask);
            imageGIF($avatarImage);
        } elseif ($format == "png") {
            $this->format = "png";
            imagePNG($avatarImage);
        } else {
            ob_end_clean();
            exit;
        }
        $resource = ob_get_contents();
        ob_end_clean();
        imageDestroy($avatarImage);

        $time_end = microtime(true);
        $this->processTime += $time_end - $time_start;

        return $resource;
    }

    //http://zavaboy.com/2007/10/06/trim_an_image_using_php_and_gd
    public function ImageTrim(&$bitmap, $bgcolor, $pad = null)
    {
        // Calculate padding for each side.
        if (isset($pad)) {
            $pp = explode(' ', $pad);
            if (isset($pp[3])) {
                $p = array((int) $pp[0], (int) $pp[1], (int) $pp[2], (int) $pp[3]);
            } else if (isset($pp[2])) {
                $p = array((int) $pp[0], (int) $pp[1], (int) $pp[2], (int) $pp[1]);
            } else if (isset($pp[1])) {
                $p = array((int) $pp[0], (int) $pp[1], (int) $pp[0], (int) $pp[1]);
            } else {
                $p = array_fill(0, 4, (int) $pp[0]);
            }
        } else {
            $p = array_fill(0, 4, 0);
        }
        // Get the image width and height.
        $imw = imagesx($bitmap);
        $imh = imagesy($bitmap);
        // Set the X variables.
        $xmin = $imw;
        $xmax = 0;
        // Start scanning for the edges.
        for ($iy = 0; $iy < $imh; $iy++) {
            $first = true;
            for ($ix = 0; $ix < $imw; $ix++) {
                $ndx = imagecolorat($bitmap, $ix, $iy);
                if ($ndx != $bgcolor) {
                    if ($xmin > $ix) {$xmin = $ix;}
                    if ($xmax < $ix) {$xmax = $ix;}
                    if (!isset($ymin)) {$ymin = $iy;}
                    $ymax = $iy;
                    if ($first) {$ix = $xmax;
                        $first = false;}
                }
            }
        }
        // The new width and height of the image. (not including padding)
        $imw = 1 + $xmax - $xmin; // Image width in pixels
        $imh = 1 + $ymax - $ymin; // Image height in pixels
        // Make another image to place the trimmed version in.
        $im2 = imagecreatetruecolor($imw + $p[1] + $p[3], $imh + $p[0] + $p[2]);
        // Make the background of the new image the same as the background of the old one.
        $rgba = imagecolorsforindex($bitmap, $bgcolor);
        imagefill($im2, 0, 0, imagecolorallocatealpha($im2, $rgba['red'], $rgba['green'], $rgba['blue'], $rgba['alpha']));
        imagealphablending($im2, true);
        imagesavealpha($im2, true);

        // Copy it over to the new image.
        imagecopy($im2, $bitmap, $p[3], $p[0], $xmin, $ymin, $imw, $imh);
        // To finish up, we replace the old image which is referenced.
        $bitmap = $im2;
    }

    public function setPartColor(&$resource, $color)
    {
        $replaceColor = $this->HEX2RGB($color);
        if (LITE_RECOLOR_FUNCTION) {
            imageFilter($resource, IMG_FILTER_COLORIZE, $replaceColor[0] - 255, $replaceColor[1] - 255, $replaceColor[2] - 255);
        } else {
            $width = imageSX($resource);
            $height = imageSY($resource);
            for ($y = 0; $y < $height; $y++) {
                for ($x = 0; $x < $width; $x++) {
                    $rgb = imageColorsForIndex($resource, imageColorAt($resource, $x, $y));
                    $nr = max(round($rgb['red'] * $replaceColor[0] / 255), 0);
                    $ng = max(round($rgb['green'] * $replaceColor[1] / 255), 0);
                    $nb = max(round($rgb['blue'] * $replaceColor[2] / 255), 0);
                    imageSetPixel($resource, $x, $y, imageColorAllocateAlpha($resource, $nr, $ng, $nb, $rgb['alpha']));
                }
            }
        }
        return true;
    }
    public function getColorByPaletteID($paletteID, $colorID)
    {
        $ret = $this->settings['figuredata']['palette'][$paletteID][$colorID]['color'];
        return empty($ret) ? "FFFFFF" : $ret; //reset($this->settings['figuredata']['palette'][$paletteID])
    }
    public function getPartColor($type, $partID, $colorID)
    {
        $ret = array();

        $partSet = $this->settings['figuredata']['settype'][$type];
        $cnt = array();
        $i = 0;
        foreach ((array) $partSet['set'][$partID]['part'] as $part) {
            $ret[$part['type']][$i++] = array(
                'id' => $part['id'],
                'colorable' => $part['colorable'],
                'color' => $this->getColorByPaletteID($partSet['paletteid'], (int) $colorID[(int) $part['colorindex'] - 1]),
            );
        }
        if (is_array($partSet['set'][$partID]['hidden'])) {
            foreach ($partSet['set'][$partID]['hidden'] as $key => $parttype) {
                $this->HiddenType[$parttype] = true;
            }
        }

        return $ret;
    }
    public function getActivePartSet($partSet, $addAttr = false)
    {
        $ret = array();

        $activeParts = $this->settings['partsets']['activePartSet'][$partSet]['activePart'];
        if (count($activeParts) == 0) {
            return false;
        }

        $partSetData = $this->settings['partsets']['partSet'];
        foreach ($activeParts as $key => $type) {
            $ret[$type]['active'] = true;
            if ($addAttr) {
                $partData = $partSetData[$type];
                $ret[$type]['remove'] = $partData['remove-set-type'];
                $ret[$type]['flip'] = $partData['flipped-set-type'];
                $ret[$type]['swim'] = $partData['swim'];
            }
        }
        return $ret;
    }
    public function getDrawOrder($action, $direction)
    {
        $drawOrder = $this->settings['draworder'][$action][$direction];
        if (count($drawOrder) == 0) {
            return false;
        }

        return $drawOrder;
    }
    public function getFrameNumber($type, $action, $frame)
    {
        //TODO
        /*
        $frameSet = $this->settings['animation']['action'];
        if($this->getKeyByAttr($frameSet, "id", $action) == -1) return 0;
        $frameSet = $frameSet[$this->getKeyByAttr($frameSet, "id", $action)];
        if(count($frameSet) == 0) return 0;
        $frameSet = $frameSet['part'][$this->getKeyByAttr($frameSet['part'], "set-type", $type)];
        if(count($frameSet) == 0) return 0;
        $data = $this->getAttr($frameSet['frame'][$frame], "number");
        return $data !== false ? $data : 0;
         */
        return $frame;
    }
    public function getPartUniqueName($type, $partId)
    {
        $uniqueName = $this->settings['map'][$type][$partId];
        if (empty($uniqueName) && $type == "hd") {
            $uniqueName = $this->settings['map']["hd"][180];
        }

        if (empty($uniqueName) && $type == "hrb") {
            $uniqueName = $this->settings['map']["hr"][$partId];
        }

        if (empty($uniqueName)) {
            $uniqueName = $this->settings['map'][$type][1];
        }

        $uniqueName = str_replace("_50_", "_", $uniqueName);
        // if($this->isSmall && strstr($uniqueName, "hh_human_")) $uniqueName = str_replace("hh_human_", "hh_human_50_", $uniqueName);
        return $uniqueName;
    }
    public function getPartResourcePosition($uniqueName, $resourceName, $width = 0)
    {
        $ret = array();
        if (isset($this->settings['offset'][$uniqueName][$resourceName])) {
            $ret = $this->settings['offset'][$uniqueName][$resourceName];
        } else {
            $direction = 6 - (int) substr($resourceName, -3, 1);
            $ret = $this->settings['offset'][$uniqueName][substr_replace($resourceName, $direction, -3, 1)];
            $ret['x'] = 0 - ($this->rectWidth + $ret['x']) + $width;
        }
        return $ret;
    }
    public function buildResourceName($action, $type, $partId, $direction, $frame)
    {
        $resourceName = "";

        $resourceName .= "h"; //$this->isSmall ? "sh" : "h";
        $resourceName .= "_";
        $resourceName .= $action;
        $resourceName .= "_";
        $resourceName .= $type;
        $resourceName .= "_";
        $resourceName .= $partId;
        $resourceName .= "_";
        $resourceName .= $direction;
        $resourceName .= "_";
        $resourceName .= $frame;

        return $resourceName;
    }
    public function getPartResource($uniqueName, $action, $type, $partId, $direction)
    {
        if (!isset($this->settings['offset'][$uniqueName])) {
            $dataJson = $this->getJSON(PATH_RESOURCE . "/clothes/". $uniqueName . ".json");
            $this->settings['offset'][$uniqueName] = $dataJson["offsets"];
            $this->settings['image'][$uniqueName] = $dataJson["images"];
        }

        $frame = $this->getFrameNumber($type, $action, @(int) $this->frame[$action]);
        $isFlip = false;

        $resDirection = $direction;

        $resourceName = $this->buildResourceName($action, $type, $partId, $resDirection, $frame);

        if (!isset($this->settings['image'][$uniqueName][$resourceName]) && $action == "std") {
            $resourceName = $this->buildResourceName("spk", $type, $partId, $resDirection, $frame);
        }

        if (!isset($this->settings['image'][$uniqueName][$resourceName]) && $action != "std") {
            $action = "std";
            $resourceName = $this->buildResourceName("std", $type, $partId, $resDirection, $frame);
        }

        if (!isset($this->settings['image'][$uniqueName][$resourceName])) {
            if ($direction > 3 && $direction < 7) {
                $isFlip = false;
                $flippedType = $this->settings['partsets']['partSet'][$type]['flipped-set-type'];
                if ($flippedType != "") {
                    $resourceName = $this->buildResourceName($action, $flippedType, $partId, $resDirection, $frame);
                }

                if (!isset($this->settings['image'][$uniqueName][$resourceName]) && $action == "std") {
                    $resourceName = $this->buildResourceName("spk", $flippedType, $partId, $resDirection, $frame);
                }

                if (!isset($this->settings['image'][$uniqueName][$resourceName])) {
                    $isFlip = true;
                    $direction = 6 - $direction;
                    $resourceName = $this->buildResourceName($action, $type, $partId, $direction, $frame);
                }

                if (!isset($this->settings['image'][$uniqueName][$resourceName])) {
                    $resourceName = $this->buildResourceName($action, $flippedType, $partId, $direction, $frame);
                }

                if (!isset($this->settings['image'][$uniqueName][$resourceName]) && $action == "std") {
                    $resourceName = $this->buildResourceName("spk", $type, $partId, $direction, $frame);
                }

                if (!isset($this->settings['image'][$uniqueName][$resourceName])) {
                    return false;
                }

            } else {
                return false;
            }

        }

        $resource = imagecreatefromstring(base64_decode($this->settings['image'][$uniqueName][$resourceName]));

        $this->setResample($resource, $isFlip);

        $resourceBaseName = $this->buildResourceName($action, $type, $partId, $direction, $frame);
        $data = array(
            "resource" => $resource,
            "lib" => $uniqueName,
            "name" => $resourceBaseName,
            "filename" => $resourceName,
            "isFlip" => $isFlip,
            "width" => imageSX($resource),
            "height" => imageSY($resource),
            "offset" => $this->getPartResourcePosition($uniqueName, $resourceBaseName, imageSX($resource)),
        );

        return $data;
    }
    public function setResample(&$resource, $isFlip)
    {
        $width = imagesx($resource);
        $height = imagesy($resource);
        $temp = imageCreateTrueColor($width, $height);
        imageAlphaBlending($temp, false);
        $rectMask = imageColorAllocateAlpha($temp, 0, 0, 0, 127);
        imageSaveAlpha($temp, false);
        $x = imageCopyResampled($temp, $resource, 0, 0, ($isFlip ? ($width - 1) : 0), 0, $width, $height, ($isFlip ? (0 - $width) : $width), $height);
        if ($x) {
            $resource = $temp;
        }
        return true;
    }
}