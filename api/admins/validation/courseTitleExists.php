<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../../core/initialize.php');
include_once '../../../models/course.php';

$course = new Course($db);


$course_title = isset($_GET['course_title']) ?  $_GET['course_title'] : 0;

if (isset($course_title)) {
    if(empty($course_title) or $course_title == '0' ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid course title ')
        );
    } else {

//stuff to check course title starts here

$course->title = $course_title;

$result = $course->titleExists();
$row_count = $result->rowCount();

if ($row_count > 0) {
   
    echo json_encode(
        array('result' => '1' ,'message' => 'course title exists')
    );
} else {
    // http_response_code(404);
    echo json_encode(
        array('result' => '0' ,'message' => 'course title doesnt exists')
    );
}

//stuff to check course title ends here


    }

}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Course Title')
    );
}

?>

