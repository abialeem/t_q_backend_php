<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/teacher.php';

$teacher = new Teacher($db);

$teacher_id = isset($_GET['teacher_id']) ?  $_GET['teacher_id'] : 0;

if (isset($teacher_id)) {
    if(empty($teacher_id) or $teacher_id == '0' or $teacher_id == null or $teacher_id == "null" ) {
       // http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid teacher id ')
        );
    } else {

        $teacher->id = $teacher_id;

        $result = $teacher->getSingleTeacherById();
        $row_count = $result->rowCount();
        
        if ($row_count > 0) {
            $teacher_arr = array();
            $teacher_arr['data'] = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                 extract($row);

                $teacher = array(
                    'id' => $id,
            'title' => $title,
            'address' => $address,
            'madrasa_id' => $madrasa_id,
            'user_id' => $user_id,
            'status' => $status,
            'courses_count' => $courses_count,
            'subjects_count' => $subjects_count
                );

                array_push($teacher_arr['data'], $teacher);

            }

            echo json_encode($teacher_arr);

        } else {
            //http_response_code(404);
            echo json_encode(
                array('message' => 'No teacher to be found')
            );
        }
    }
}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A teacher ID')
    );
}
?>

