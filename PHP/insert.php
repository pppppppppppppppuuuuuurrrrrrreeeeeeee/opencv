<?php

require 'config.php';

function parting($part) {
    $part = trim($part);
    $part = array_values(array_diff(explode(PHP_EOL, $part), [""]));
    $frameNo = (int)explode('.: ', $part[0])[1];
    $rects = [['frame' => $frameNo]];
    for($i = 1; $i < count($part); $i++) {
        $rectObj = explode('Rect: ', $part[$i])[1];
        $rectObj = str_replace(['[', ']', '(', ')'], ['', '', '', ''], $rectObj);
        $rectObj = explode(' from ', $rectObj);
        $startPoint = explode(', ', $rectObj[1]);
        $startPointX = (int)$startPoint[0];
        $startPointY = (int)$startPoint[1];
    
        $delta = explode(' x ', $rectObj[0]);
        $dx = $delta[0];
        $dy = $delta[1];
        $EndPointX = $dx + $startPointX;
        $EndPointY = $dy + $startPointY;
        $rects[] = [$startPointX, $startPointY, $EndPointX, $EndPointY];
    }
    return $rects;
}

$all = file_get_contents('Output');
$all = explode('-------------------------------------------------------------------------'.PHP_EOL, $all);
unset($all[6390]);
foreach($all as $frame) {
    $parting = parting($frame);
    $FrameNo = $parting[0]['frame'];
    echo($FrameNo.PHP_EOL);
    for($i = 1; $i < count($parting); $i++) {
        $startPointX = $parting[$i][0];
        $startPointY = $parting[$i][1];
        $EndPointX = $parting[$i][2];
        $EndPointY = $parting[$i][3];

        $sql = "INSERT INTO FrameRects (FrameNo, StartPointX, StartPointY, EndPointX, EndPointY) VALUES ($FrameNo, $startPointX, $startPointY, $EndPointX, $EndPointY);";
        if($conn->query($sql) !== TRUE) {
            die($sql);
        }
    }
}
