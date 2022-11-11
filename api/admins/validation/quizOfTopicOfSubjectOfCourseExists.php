<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../../core/initialize.php');
include_once '../../../models/quiz.php';

$quiz = new Quiz($db);


$quiz_title = isset($_GET['quiz_title']) ?  $_GET['quiz_title'] : 0;

$course_id = isset($_GET['course_id']) ?  $_GET['course_id'] : 0;

$subject_id = isset($_GET['subject_id']) ?  $_GET['subject_id'] : 0;

$topic_id = isset($_GET['topic_id']) ?  $_GET['topic_id'] : 0;


if (isset($quiz_title) && isset($course_id) && isset($subject_id) && isset($topic_id)) {
    if((empty($quiz_title) or $quiz_title == '0') && (empty($course_id) or $course_id == '0') && (empty($subject_id) or $subject_id == '0') && (empty($topic_id) or $topic_id == '0') ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid course id and subject id and topic id and quiz title ')
        );
    } else {

//stuff to check quiz title starts here

$quiz->title = $quiz_title;

$quiz->course_id = $course_id;

$quiz->subject_id = $subject_id;

$quiz->topic_id = $topic_id;

$result = $quiz->quizExists();
$row_count = $result->rowCount();

if ($row_count > 0) {
   
    echo json_encode(
        array('result' => '1' ,'message' => 'quiz for this topic for this subject for this course exists')
    );
} else {
    // http_response_code(404);
    echo json_encode(
        array('result' => '0' ,'message' => 'quiz for this topic for this subject for this course doesnt exists')
    );
}

//stuff to check quiz title ends here


    }

}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Course ID And Subject ID And Topic ID And Quiz Title')
    );
}

?>

