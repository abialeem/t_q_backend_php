<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/topic.php';
include_once '../../models/subject.php';
include_once '../../models/course.php';
include_once '../../models/video.php';

$video = new Video($db);

$topic = new Topic($db);

$subject = new Subject($db);

$course = new Course($db);

$subject_id = isset($_GET['subject_id']) ?  $_GET['subject_id'] : 0;

if (isset($subject_id)) {
    if(empty($subject_id) or $subject_id == '0' ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid subject id ')
        );
    } else {

        $video->subject_id = $subject_id;


        $result = $video->getSingleSubjectVideos();
        $row_count = $result->rowCount();
        
        $video_arr = array();
        $video_arr['data'] = array();

        if ($row_count > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $course->id = $row['course_id'];
                $subject->id = $row['subject_id'];
                $topic->id = $row['topic_id'];
                $result2 = $course->getSingleCourseTitleById();
                $course_row = $result2->fetch(PDO::FETCH_ASSOC);

                $result3 = $subject->getSingleSubjectTitleById();
                $subject_row = $result3->fetch(PDO::FETCH_ASSOC);

                $result4 = $topic->getSingleTopicTitleById();
                $topic_row = $result4->fetch(PDO::FETCH_ASSOC);

                $video = array(
                    'id' => $id,
                    'title' => $title,
                    'description' => $description,
                    'topic_id' => $topic_id,
                    'subject_id' => $subject_id,
                    'course_id' => $course_id,
                    'course_title' => $course_row['title'],
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
                'title' => 'no video for this subject present'
                )
        );
            echo json_encode($video_arr);
        }


    }


}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Subject ID')
    );
}


?>

