<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/topic.php';

$topic = new Topic($db);

$result = $topic->getAllTopics();
$row_count = $result->rowCount();

if ($row_count > 0) {
    $topic_arr = array();
    $topic_arr['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $topic = array(
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'subject_id' => $subject_id,
            'course_id' => $course_id,
            'serial_no' => $serial_no,
            'video_count' => $video_count,
            'quiz_count' => $quiz_count
            );
        array_push($topic_arr['data'], $topic);
    }
    echo json_encode($topic_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array('message' => 'No topics to be found')
    );
}
?>

