<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/course.php';


$course = new Course($db);

$course_id = isset($_GET['course_id']) ?  $_GET['course_id'] : 0;

if (isset($course_id)) {
    if(empty($course_id) or $course_id == '0' or $course_id == null or $course_id == "null" ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid course id ')
        );
    } else {

        $course->id = $course_id;

        $result = $course->getSingleCourseById();
        $row_count = $result->rowCount();
        
        if ($row_count > 0) {
            $course_arr = array();
            $course_arr['data'] = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                 extract($row);
                $course = array(
                    'id' => $id,
                    'title' => $title,
                    'description' => $description,
                    'course_type' => $course_type,
                    'skill_level' => $skill_level,
                    'language' => $language,
                    'subjects_count' => $subjects_count,
                    'status' => $status,
                );
                array_push($course_arr['data'], $course);
            }
            echo json_encode($course_arr);
        } else {
            http_response_code(404);
            echo json_encode(
                array('message' => 'No course to be found')
            );
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

