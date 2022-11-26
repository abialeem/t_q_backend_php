<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/topic.php';
include_once '../../models/subject.php';

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

        $topic->course_id = $course_id;


        $result = $topic->getSingleCourseTopics();
        $row_count = $result->rowCount();
        
        $topic_arr = array();
        $topic_arr['data'] = array();

        if ($row_count > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                    $subject->id = $row['subject_id'];

                    $result2 = $subject->getSingleSubjectTitleById();
                    $subject_row = $result2->fetch(PDO::FETCH_ASSOC);

                $topic = array(
                    'id' => $id,
                    'title' => $title,
                    'description' => $description,
                    'subject_id' => $subject_id,
                    'subject_title' => $subject_row['title'],
                    'course_id' => $course_id,
                    'serial_no' => $serial_no,
                    'video_count' => $video_count,
                    'quiz_count' => $quiz_count,
                    'status' => $status,
                    );
                array_push($topic_arr['data'], $topic);
            }

            echo json_encode($topic_arr);

        } else {
            array_push($topic_arr['data'], 
            array(
                'id' => 'null',
                'title' => 'no topics for this course present'
                )
        );
            echo json_encode($topic_arr);
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

