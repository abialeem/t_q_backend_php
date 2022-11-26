<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/subject.php';
include_once '../../models/course.php';


$subject = new Subject($db);

$course = new Course($db);

$subject_id = isset($_GET['subject_id']) ?  $_GET['subject_id'] : 0;

if (isset($subject_id)) {
    if(empty($subject_id) or $subject_id == '0' or $subject_id == null or $subject_id == "null" ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid subject id ')
        );
    } else {

        $subject->id = $subject_id;

        $result = $subject->getSingleSubjectById();
        $row_count = $result->rowCount();
        
        if ($row_count > 0) {
            $subject_arr = array();
            $subject_arr['data'] = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                 extract($row);
                    $course->id = $row['course_id'];
                    $result2 = $course->getSingleCourseTitleById();
                    $course_row = $result2->fetch(PDO::FETCH_ASSOC);

                $subject = array(
                    'id' => $id,
                    'title' => $title,
                    'description' => $description,
                    'course_id' => $course_id,
                    'course_title' => $course_row['title'],
                    'serial_no' => $serial_no,
                    'topic_count' => $topic_count,
                    'status' => $status
                );
                array_push($subject_arr['data'], $subject);
            }
            echo json_encode($subject_arr);
        } else {
            http_response_code(404);
            echo json_encode(
                array('message' => 'No subject to be found')
            );
        }
    }
}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A subject ID')
    );
}
?>

