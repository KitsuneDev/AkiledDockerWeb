<?php
$data = json_decode(file_get_contents('php://input'));
if (isset($data)) {
    $type = $data->type;
    
    $userID = $data->user_id;
    $roomID = $data->room_id;
    $image = base64_decode($data->base_64);
    $timestamp = $data->timestamp;

    if ($type === 'thumbnail') {
        $path = "./thumbnail/" . $roomID . ".png";

        $status = file_put_contents($path, $image);
        if ($status) {
            output([
                "status" => true,
                "preview" => "thumbnail/" . $roomID . ".png",
                "full_preview" => "http://hotel.localhost/swfs/newfoto/thumbnail/" . $roomID . ".png"
            ]);
            return;
        }
    } else if ($type === 'camera') {
        $encrypted = md5($roomID . "-" . $timestamp);
        $path = "./photos/" . $encrypted . ".png";
        $pathSmall = "./photos/" . $encrypted . "_small.png";

        $status = file_put_contents($path, $image);
        if ($status) {
            file_put_contents($pathSmall, $image);

            output([
                "status" => true,
                "preview" => "photos/" . $encrypted . ".png",
                "full_preview" => "http://hotel.localhost/swfs/newfoto/photos/" . $encrypted . ".png",
                "encrypted_id" => $encrypted,
                "timestamp" => $timestamp,
                "room_id" => $roomID
            ]);
            return;
        }
    }
}

output([
    "status" => false
]);

function output($data) {
    header("Content-Type: application/json");

    echo json_encode($data);
}