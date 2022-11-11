<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/video.php';

$video = new Video($db);

$course_id = isset($_GET['course_id']) ?  $_GET['course_id'] : 0;

$subject_id = isset($_GET['subject_id']) ?  $_GET['subject_id'] : 0;

$topic_id = isset($_GET['topic_id']) ?  $_GET['topic_id'] : 0;

if (isset($course_id) && isset($subject_id) && isset($topic_id)) {
    if( (empty($course_id) or $course_id == '0') && (empty($subject_id) or $subject_id == '0') && (empty($topic_id) or $topic_id == '0') ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid course ID and subject ID and topic ID ')
        );
    } else {

$video->course_id = $course_id;

$video->subject_id = $subject_id;

$video->topic_id = $topic_id;

$result = $video->getAllVideosSerialsByCourseIDSubjectIDTopicID();
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
        array('message' => 'Please Provide A Course ID And A Subject ID And A Topic ID')
    );
}


?>

