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
include_once '../../models/course.php';

$quiz = new Quiz($db);

$topic = new Topic($db);

$subject = new Subject($db);

$course = new Course($db);

$quiz_id = isset($_GET['quiz_id']) ?  $_GET['quiz_id'] : 0;

if (isset($quiz_id)) {
    if(empty($quiz_id) or $quiz_id == '0' or $quiz_id == null or $quiz_id == "null" ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid quiz id ')
        );
    } else {

        $quiz->id = $quiz_id;

        $result = $quiz->getSingleQuizById();
        $row_count = $result->rowCount();
        
        if ($row_count > 0) {
            $quiz_arr = array();
            $quiz_arr['data'] = array();
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

                $quiz = array(
                    'id' => $id,
                    'title' => $title,
                    'description' => $description,
                    'topic_id' => $topic_id,
                    'subject_id' => $subject_id,
                    'course_id' => $course_id,
                    'course_title' =>$course_row['title'],
                    'subject_title' =>$subject_row['title'],
                    'topic_title' =>$topic_row['title'],
                    'serial_no' => $serial_no,
                    'question_count' => $question_count,
                    'attachment_count' => $attachment_count,
                    'status' => $status,
                );
                array_push($quiz_arr['data'], $quiz);
            }
            echo json_encode($quiz_arr);
        } else {
            http_response_code(404);
            echo json_encode(
                array('message' => 'No quiz to be found')
            );
        }
    }
}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A quiz ID')
    );
}
?>

