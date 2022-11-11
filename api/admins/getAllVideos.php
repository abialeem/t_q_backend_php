<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/video.php';

$video = new Video($db);

$result = $video->getAllVideos();
$row_count = $result->rowCount();

if ($row_count > 0) {
    $video_arr = array();
    $video_arr['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $video = array(
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'topic_id' => $topic_id,
            'subject_id' => $subject_id,
            'course_id' => $course_id,
            'serial_no' => $serial_no,
            'video_src' => $video_src,
            'attachment_count' => $attachment_count,
            );
        array_push($video_arr['data'], $video);
    }
    echo json_encode($video_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array('message' => 'No videos to be found')
    );
}
?>

