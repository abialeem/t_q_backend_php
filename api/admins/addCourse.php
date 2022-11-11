<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../../core/initialize.php';
include_once '../../models/course.php';

$course= new Course($db);

$data = json_decode(file_get_contents("php://input"));

if (isset($data->title) && isset($data->description) && isset($data->course_type) && isset($data->skill_level) && isset($data->language) ) {
    if (empty($data->title) || empty($data->description) || empty($data->course_type) || empty($data->skill_level) || empty($data->language)) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please enter all the required fields to add a course')
        );
    } else {
        $course->title = $data->title;
        $course->description = $data->description;
        $course->course_type = $data->course_type;
        $course->skill_level = $data->skill_level;
        $course->language = $data->language;
       
            $result = $course->titleExists();
            $row_count = $result->rowCount();
            if ($row_count > 0) {
                http_response_code(422);
                echo json_encode(
                    array('message' => 'course Already Exists. Please try Valid New course Details')
                );
            } else {
                if ($course->addCourse()) {
                    http_response_code(201);
                    echo json_encode(
                        array('message' => 'Course Added successfully')
                    );
                } else {
                    http_response_code(500);
                    echo json_encode(
                        array('message' => 'course could not be added')
                    );
                }
            }
        
    }
} else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please enter all the required fields')
    );
}
?>