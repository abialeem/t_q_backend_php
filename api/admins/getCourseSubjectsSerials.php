<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/subject.php';

$subject = new Subject($db);

$course_id = isset($_GET['course_id']) ?  $_GET['course_id'] : 0;

if (isset($course_id)) {
    if(empty($course_id) or $course_id == '0' ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid course ID ')
        );
    } else {

$subject->course_id = $course_id;

$result = $subject->getAllSubjectsSerialsByCourseID();
$row_count = $result->rowCount();


$subject_arr = array();
$subject_arr['data'] = array();

if ($row_count > 0) {
    
    
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $subject =  $serial_no ;
        array_push($subject_arr['data'], $subject);
    }
    echo json_encode($subject_arr);
} else {
    array_push($subject_arr['data'], 0);
    echo json_encode($subject_arr);
}



    }

}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Course ID')
    );
}


?>

