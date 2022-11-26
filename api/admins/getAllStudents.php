<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/student.php';
include_once '../../models/madrasa.php';
include_once '../../models/course.php';

$student = new Student($db);

$madrasa = new Madrasa($db);

$course = new Course($db);

$result = $student->getAllStudents();
$row_count = $result->rowCount();

if ($row_count > 0) {
    $student_arr = array();
    $student_arr['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $course->id = $row['course_id'];
        $madrasa->id = $row['madrasa_id'];

        $result2 = $course->getSingleCourseTitleById();
        $course_row = $result2->fetch(PDO::FETCH_ASSOC);

        $result3 = $madrasa->getSingleMadrasaTitleById();
        $madrasa_row = $result3->fetch(PDO::FETCH_ASSOC);

        if($row['madrasa_id']=='0'){
            $madrasa_row['madrasa_title'] = "not assigned yet"; 
           }
        if($row['course_id']=='0'){
            $course_row['title'] = "not assigned yet"; 
           }


        $student = array(
            'id' => $id,
            'title' => $title,
            'address' => $address,
            'user_id' => $user_id,
            'madrasa_id' => $madrasa_id,
            'madrasa_title' => $madrasa_row['madrasa_title'],
            'course_title' => $course_row['title'],
            'course_id' => $course_id,
            'its_number' => $its_number,
            'status' => $status
            );
        array_push($student_arr['data'], $student);
    }
    echo json_encode($student_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array('message' => 'No students to be found')
    );
}
?>

