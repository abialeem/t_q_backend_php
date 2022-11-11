<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../../core/initialize.php');
include_once '../../../models/topic.php';

$topic = new Topic($db);


$topic_title = isset($_GET['topic_title']) ?  $_GET['topic_title'] : 0;

$course_id = isset($_GET['course_id']) ?  $_GET['course_id'] : 0;

$subject_id = isset($_GET['subject_id']) ?  $_GET['subject_id'] : 0;


if (isset($topic_title) && isset($course_id) && isset($subject_id)) {
    if((empty($topic_title) or $topic_title == '0') && (empty($course_id) or $course_id == '0') && (empty($subject_id) or $subject_id == '0') ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid course id and subject id and topic title ')
        );
    } else {

//stuff to check topic title starts here

$topic->title = $topic_title;

$topic->course_id = $course_id;

$topic->subject_id = $subject_id;

$result = $topic->topicExists();
$row_count = $result->rowCount();

if ($row_count > 0) {
   
    echo json_encode(
        array('result' => '1' ,'message' => 'topic for this subject for this course exists')
    );
} else {
    // http_response_code(404);
    echo json_encode(
        array('result' => '0' ,'message' => 'topic for this subject for this course doesnt exists')
    );
}

//stuff to check topic title ends here


    }

}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Course ID And Subject ID And Topic Title')
    );
}

?>

