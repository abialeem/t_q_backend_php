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

        $topic->subject_id = $subject_id;


        $result = $topic->getSingleSubjectTopics();
        $row_count = $result->rowCount();
        
        $topic_arr = array();
        $topic_arr['data'] = array();

        if ($row_count > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $course->id = $row['course_id'];
                $subject->id = $row['subject_id'];
                $result2 = $course->getSingleCourseTitleById();
                $course_row = $result2->fetch(PDO::FETCH_ASSOC);

                $result3 = $subject->getSingleSubjectTitleById();
                $subject_row = $result3->fetch(PDO::FETCH_ASSOC);
                $topic = array(
                    'id' => $id,
                    'title' => $title,
                    'description' => $description,
                    'course_id' => $course_id,
                    'subject_id' => $subject_id,
                    'course_title' =>$course_row['title'],
                    'subject_title' =>$subject_row['title'],
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
                'title' => 'no topics for this subject present'
                )
        );
            echo json_encode($topic_arr);
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

