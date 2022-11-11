<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/quiz.php';

$quiz = new Quiz($db);

$result = $quiz->getAllQuizzes();
$row_count = $result->rowCount();

if ($row_count > 0) {
    $quiz_arr = array();
    $quiz_arr['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $quiz = array(
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'topic_id' => $topic_id,
            'subject_id' => $subject_id,
            'course_id' => $course_id,
            'serial_no' => $serial_no,
            'question_count' => $question_count,
            'attachment_count' => $attachment_count,
            );
        array_push($quiz_arr['data'], $quiz);
    }
    echo json_encode($quiz_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array('message' => 'No quizzes to be found')
    );
}
?>

