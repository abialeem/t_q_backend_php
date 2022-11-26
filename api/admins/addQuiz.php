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
include_once '../../models/quiz.php';
include_once '../../models/topic.php';


$topic= new Topic($db);

$quiz = new Quiz($db);

$data = json_decode(file_get_contents("php://input"));

if (isset($data->title) && isset($data->description) && isset($data->course_id) && isset($data->subject_id) && isset($data->topic_id) && isset($data->serial_no) ) {
    if (empty($data->title) || empty($data->description) || empty($data->course_id) || empty($data->subject_id) || empty($data->serial_no) || empty($data->topic_id) ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please enter all the required fields to add a quiz')
        );
    } else {
        $quiz->title = $data->title;
        $quiz->description = $data->description;
        $quiz->course_id = $data->course_id;
        $quiz->subject_id = $data->subject_id;
        $quiz->topic_id = $data->topic_id;
        $quiz->serial_no = $data->serial_no;
        $quiz->question_count = 0;
        $quiz->attachment_count = 0;
        $quiz->status = 1;

       
            $result = $quiz->quizExists();
            $row_count = $result->rowCount();
            if ($row_count > 0) {
                http_response_code(422);
                echo json_encode(
                    array('message' => 'Quiz For This Topic Of Subject Of Provided Course Already Exists. Please try Valid New Quiz Details')
                );
            } else {
                if ($quiz->addQuiz()) {
                        //increment quiz count of topic_id in topics db
                        $topic->id = $data->topic_id;
                        if ($topic->incrementQuizCount()) {
                            http_response_code(201);
                            echo json_encode(
                                array('message' => 'Quiz Added Successfully')
                            );
                        }
                        else{
                            http_response_code(201);
                            echo json_encode(
                                array('message' => 'Quiz Added successfully but error in quiz_count')
                            );

                        }

                   
                } else {
                    http_response_code(500);
                    echo json_encode(
                        array('message' => 'quiz could not be added')
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