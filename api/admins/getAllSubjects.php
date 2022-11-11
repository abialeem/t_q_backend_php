<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/subject.php';

$subject = new Subject($db);

$result = $subject->getAllSubjects();
$row_count = $result->rowCount();

if ($row_count > 0) {
    $subject_arr = array();
    $subject_arr['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $subject = array(
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'course_id' => $course_id,
            'serial_no' => $serial_no,
            'topic_count' => $topic_count
            );
        array_push($subject_arr['data'], $subject);
    }
    echo json_encode($subject_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array('message' => 'No subjects to be found')
    );
}
?>

