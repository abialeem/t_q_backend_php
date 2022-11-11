<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../../core/initialize.php');
include_once '../../../models/video.php';

$video = new Video($db);


$video_title = isset($_GET['video_title']) ?  $_GET['video_title'] : 0;

$course_id = isset($_GET['course_id']) ?  $_GET['course_id'] : 0;

$subject_id = isset($_GET['subject_id']) ?  $_GET['subject_id'] : 0;

$topic_id = isset($_GET['topic_id']) ?  $_GET['topic_id'] : 0;


if (isset($video_title) && isset($course_id) && isset($subject_id) && isset($topic_id)) {
    if((empty($video_title) or $video_title == '0') && (empty($course_id) or $course_id == '0') && (empty($subject_id) or $subject_id == '0') && (empty($topic_id) or $topic_id == '0') ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid course id and subject id and topic id and video title ')
        );
    } else {

//stuff to check video title starts here

$video->title = $video_title;

$video->course_id = $course_id;

$video->subject_id = $subject_id;

$video->topic_id = $topic_id;

$result = $video->videoExists();
$row_count = $result->rowCount();

if ($row_count > 0) {
   
    echo json_encode(
        array('result' => '1' ,'message' => 'video for this topic for this subject for this course exists')
    );
} else {
    // http_response_code(404);
    echo json_encode(
        array('result' => '0' ,'message' => 'video for this topic for this subject for this course doesnt exists')
    );
}

//stuff to check video title ends here


    }

}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Course ID And Subject ID And Topic ID And Video Title')
    );
}

?>

