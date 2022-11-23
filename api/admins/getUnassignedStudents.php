<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/student.php';

$student = new Student($db);

$result = $student->getUnassignedStudents();
$row_count = $result->rowCount();

if ($row_count > 0) {
    $student_arr = array();
    $student_arr['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $student = array(
            'id' => $id,
            'title' => $title,
            'address' => $address,
            'user_id' => $user_id,
            'madrasa_id' => $madrasa_id,
            'course_id' => $course_id,
            'its_number' => $its_number,
            'status' => $status
        );
        array_push($student_arr['data'], $student);
    }
    echo json_encode($student_arr);
} else {
    // http_response_code(404);
    echo json_encode(
        array('message' => 'No students to be found')
    );
}
?>

