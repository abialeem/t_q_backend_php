<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/video.php';

$video = new Video($db);

$topic_id = isset($_GET['topic_id']) ?  $_GET['topic_id'] : 0;

if (isset($topic_id)) {
    if(  empty($topic_id) or $topic_id == '0' ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid topic ID ')
        );
    } else {

$video->topic_id = $topic_id;

$result = $video->getAllVideosSerialsByTopicID();
$row_count = $result->rowCount();


$video_arr = array();
$video_arr['data'] = array();

if ($row_count > 0) {
    
    
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $video =  $serial_no ;
        array_push($video_arr['data'], $video);
    }
    echo json_encode($video_arr);
} else {
    array_push($video_arr['data'], 0);
    echo json_encode($video_arr);
}



    }

}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A  Topic ID')
    );
}


?>

