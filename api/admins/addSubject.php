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
include_once '../../models/subject.php';

$subject= new Subject($db);

$data = json_decode(file_get_contents("php://input"));

if (isset($data->title) && isset($data->description) && isset($data->course_id) && isset($data->serial_no)  ) {
    if (empty($data->title) || empty($data->description) || empty($data->course_id) || empty($data->serial_no) ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please enter all the required fields to add a subject')
        );
    } else {
        $subject->title = $data->title;
        $subject->description = $data->description;
        $subject->course_id = $data->course_id;
        $subject->serial_no = $data->serial_no;
        $subject->topic_count = 0;
       
            $result = $subject->subjectExists();
            $row_count = $result->rowCount();
            if ($row_count > 0) {
                http_response_code(422);
                echo json_encode(
                    array('message' => 'Subject For This Course Already Exists. Please try Valid New subject Details')
                );
            } else {
                if ($subject->addSubject()) {
                    http_response_code(201);
                    echo json_encode(
                        array('message' => 'Subject Added successfully')
                    );
                } else {
                    http_response_code(500);
                    echo json_encode(
                        array('message' => 'subject could not be added')
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