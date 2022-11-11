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
include_once '../../models/topic.php';
include_once '../../models/subject.php';

$topic= new Topic($db);

$subject = new Subject($db);

$data = json_decode(file_get_contents("php://input"));

if (isset($data->title) && isset($data->description) && isset($data->course_id) && isset($data->subject_id) && isset($data->serial_no)  ) {
    if (empty($data->title) || empty($data->description) || empty($data->course_id) || empty($data->subject_id) || empty($data->serial_no) ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please enter all the required fields to add a topic')
        );
    } else {
        $topic->title = $data->title;
        $topic->description = $data->description;
        $topic->course_id = $data->course_id;
        $topic->subject_id = $data->subject_id;
        $topic->serial_no = $data->serial_no;
        $topic->video_count = 0;
        $topic->quiz_count = 0;
       
            $result = $topic->topicExists();
            $row_count = $result->rowCount();
            if ($row_count > 0) {
                http_response_code(422);
                echo json_encode(
                    array('message' => 'Topic For This Subject Of Provided Course Already Exists. Please try Valid New Topic Details')
                );
            } else {
                if ($topic->addTopic()) {
                        //increment topic count of subject_id
                        $subject->id = $data->subject_id;
                        if ($subject->incrementTopicCount()) {
                            http_response_code(201);
                            echo json_encode(
                                array('message' => 'Topic Added successfully')
                            );
                        }
                        else{
                            http_response_code(201);
                            echo json_encode(
                                array('message' => 'Topic Added successfully but error in topic_count')
                            );

                        }

                   
                } else {
                    http_response_code(500);
                    echo json_encode(
                        array('message' => 'topic could not be added')
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