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
include_once '../../models/video.php';
include_once '../../models/topic.php';


$topic= new Topic($db);

$video = new Video($db);

$data = json_decode(file_get_contents("php://input"));

if (isset($data->title) && isset($data->description) && isset($data->course_id) && isset($data->subject_id) && isset($data->topic_id) && isset($data->serial_no) && isset($data->video_src) && isset($data->video_id) ) {
    if (empty($data->title) || empty($data->description) || empty($data->course_id) || empty($data->subject_id) || empty($data->serial_no) || empty($data->topic_id) || empty($data->video_src) || empty($data->video_id)  ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please enter all the required fields to add a video')
        );
    } else {
        $video->title = $data->title;
        $video->description = $data->description;
        $video->course_id = $data->course_id;
        $video->subject_id = $data->subject_id;
        $video->topic_id = $data->topic_id;
        $video->serial_no = $data->serial_no;
        $video->video_src = $data->video_src;
        $video->video_id = $data->video_id;
        $video->attachment_count = 0;
        $video->status = 1;

       
            $result = $video->videoExists();
            $row_count = $result->rowCount();
            if ($row_count > 0) {
                http_response_code(422);
                echo json_encode(
                    array('message' => 'Video For This Topic Of Subject Of Provided Course Already Exists. Please try Valid New Video Details')
                );
            } else {
                if ($video->addVideo()) {
                        //increment video count of topic_id in topics db
                        $topic->id = $data->topic_id;
                        if ($topic->incrementVideoCount()) {
                            http_response_code(201);
                            echo json_encode(
                                array('message' => 'Video Added successfully')
                            );
                        }
                        else{
                            http_response_code(201);
                            echo json_encode(
                                array('message' => 'Video Added successfully but error in video_count')
                            );

                        }

                   
                } else {
                    http_response_code(500);
                    echo json_encode(
                        array('message' => 'video could not be added')
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