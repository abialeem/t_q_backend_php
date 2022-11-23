<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/student.php';

$student = new Student($db);


$madrasa_id = isset($_GET['madrasa_id']) ?  $_GET['madrasa_id'] : 0;

if (isset($madrasa_id)) {

    if(empty($madrasa_id) or $madrasa_id == '0' ) {
       // http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid madrasa id ')
        );
    } else {

        $student->madrasa_id = $madrasa_id;

        $result = $student->getStudentsByMadrasaId();
        $row_count = $result->rowCount();


        $student_arr= array();
        $student_arr['data'] = array();
        if ($row_count > 0) {
            
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                 extract($row);
                $student = array(
                    'id' => $id,
                    'title' => $title,
                    'address' => $address,
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
        
    }


}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Madrasa ID')
    );
}



?>

