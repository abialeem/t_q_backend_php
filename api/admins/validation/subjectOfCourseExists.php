<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../../core/initialize.php');
include_once '../../../models/subject.php';

$subject = new Subject($db);


$subject_title = isset($_GET['subject_title']) ?  $_GET['subject_title'] : 0;

$course_id = isset($_GET['course_id']) ?  $_GET['course_id'] : 0;

if (isset($subject_title) && isset($course_id)) {
    if((empty($subject_title) or $subject_title == '0') && (empty($course_id) or $course_id == '0') ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid course id and subject title ')
        );
    } else {

//stuff to check course title starts here

$subject->title = $subject_title;

$subject->course_id = $course_id;

$result = $subject->subjectExists();
$row_count = $result->rowCount();

if ($row_count > 0) {
   
    echo json_encode(
        array('result' => '1' ,'message' => 'subject for this course exists')
    );
} else {
    // http_response_code(404);
    echo json_encode(
        array('result' => '0' ,'message' => 'subject for this course doesnt exists')
    );
}

//stuff to check course title ends here


    }

}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Course ID And Subject Title')
    );
}

?>

