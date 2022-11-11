<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/quiz.php';

$quiz = new Quiz($db);

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

$quiz->course_id = $course_id;

$quiz->subject_id = $subject_id;

$quiz->topic_id = $topic_id;

$result = $quiz->getAllQuizzesSerialsByCourseIDSubjectIDTopicID();
$row_count = $result->rowCount();


$quiz_arr = array();
$quiz_arr['data'] = array();

if ($row_count > 0) {
    
    
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $quiz =  $serial_no ;
        array_push($quiz_arr['data'], $quiz);
    }
    echo json_encode($quiz_arr);
} else {
    array_push($quiz_arr['data'], 0);
    echo json_encode($quiz_arr);
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

