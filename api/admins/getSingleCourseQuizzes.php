<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/quiz.php';
include_once '../../models/topic.php';
include_once '../../models/subject.php';

$quiz = new Quiz($db);

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

        $quiz->course_id = $course_id;


        $result = $quiz->getSingleCourseQuizzes();
        $row_count = $result->rowCount();
        
        $quiz_arr = array();
        $quiz_arr['data'] = array();

        if ($row_count > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $subject->id = $row['subject_id'];

                $topic->id = $row['topic_id'];

                $result2 = $subject->getSingleSubjectTitleById();
                $subject_row = $result2->fetch(PDO::FETCH_ASSOC);

                $result3 = $topic->getSingleTopicTitleById();
                $topic_row = $result3->fetch(PDO::FETCH_ASSOC);

                $quiz = array(
                    'id' => $id,
                    'title' => $title,
                    'description' => $description,
                    'topic_id' => $topic_id,
                    'subject_id' => $subject_id,
                    'course_id' => $course_id,
                    'subject_title' => $subject_row['title'],
                    'topic_title' => $topic_row['title'],
                    'serial_no' => $serial_no,
                    'question_count' => $question_count,
                    'attachment_count' => $attachment_count,
                    'status' => $status,
                    );
                array_push($quiz_arr['data'], $quiz);
            }

            echo json_encode($quiz_arr);

        } else {
            array_push($quiz_arr['data'], 
            array(
                'id' => 'null',
                'title' => 'no quizs for this course present'
                )
        );
            echo json_encode($quiz_arr);
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

