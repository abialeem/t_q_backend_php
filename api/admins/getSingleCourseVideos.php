<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/video.php';
include_once '../../models/topic.php';
include_once '../../models/subject.php';

$video = new Video($db);

$topic = new Topic($db);

$subject = new Subject($db);


$course_id = isset($_GET['course_id']) ?  $_GET['course_id'] : 0;

if (isset($course_id)) {
    if(empty($course_id) or $course_id == '0' ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid course id ')
        );
    } else {

        $video->course_id = $course_id;


        $result = $video->getSingleCourseVideos();
        $row_count = $result->rowCount();
        
        $video_arr = array();
        $video_arr['data'] = array();

        if ($row_count > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $subject->id = $row['subject_id'];

                $topic->id = $row['topic_id'];

                $result2 = $subject->getSingleSubjectTitleById();
                $subject_row = $result2->fetch(PDO::FETCH_ASSOC);

                $result3 = $topic->getSingleTopicTitleById();
                $topic_row = $result3->fetch(PDO::FETCH_ASSOC);

                $video = array(
                    'id' => $id,
            'title' => $title,
            'description' => $description,
            'topic_id' => $topic_id,
            'subject_id' => $subject_id,
            'course_id' => $course_id,
            'subject_title' => $subject_row['title'],
            'topic_title' => $topic_row['title'],
            'serial_no' => $serial_no,
            'video_src' => $video_src,
            'video_id' => $video_id,
            'attachment_count' => $attachment_count,
            'status' => $status,
                    );
                array_push($video_arr['data'], $video);
            }

            echo json_encode($video_arr);

        } else {
            array_push($video_arr['data'], 
            array(
                'id' => 'null',
                'title' => 'no videos for this course present'
                )
        );
            echo json_encode($video_arr);
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

