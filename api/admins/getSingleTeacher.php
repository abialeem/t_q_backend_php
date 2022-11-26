<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/teacher.php';
include_once '../../models/madrasa.php';
include_once '../../models/teacherMadrasas.php';

$teacher = new Teacher($db);

$teacher_madrasas = new TeacherMadrasas($db);

$madrasa = new Madrasa($db);

$teacher_id = isset($_GET['teacher_id']) ?  $_GET['teacher_id'] : 0;

if (isset($teacher_id)) {
    if(empty($teacher_id) or $teacher_id == '0' or $teacher_id == null or $teacher_id == "null" ) {
       // http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid teacher id ')
        );
    } else {
        $teacher_arr = array();
        $teacher_arr['data'] = array();

        $teacher->id = $teacher_id;

        $result = $teacher->getSingleTeacherById();
        $row_count = $result->rowCount();
        
        if ($row_count > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                 extract($row);

        //get madrasa_id's of each teacher if any , else set madrasa_id = 0
        $teacher_madrasa_arr = array();
        $teacher_madrasa_arr['madrasas']['id'] = array();
        $teacher_madrasa_arr['madrasas']['title'] = array();
        $teacher_madrasas->teacher_id = $row['id'];
        $teacher_madrasa_result = $teacher_madrasas->getSingleTeacherMadrasas();
        while( $teacher_madrasa_row = $teacher_madrasa_result->fetch(PDO::FETCH_ASSOC)){
                    //get madrasa title here
                $madrasa->id = $teacher_madrasa_row['madrasa_id'];
                $result2 = $madrasa->getSingleMadrasaById();
                $madrasa_row = $result2->fetch(PDO::FETCH_ASSOC);
             array_push($teacher_madrasa_arr['madrasas']['id'], $teacher_madrasa_row['madrasa_id']);          
             array_push($teacher_madrasa_arr['madrasas']['title'], $madrasa_row['madrasa_title']);

        }
        
        // echo json_encode($teacher_madrasa_arr['madrasas']);
       if($teacher_madrasa_arr['madrasas']['id']==[]){
        $teacher_madrasa_arr['madrasas']['title'] = "not assigned yet"; 
        $teacher_madrasa_arr['madrasas']['id'] = "not assigned yet"; 
       }
        $teacher = array(
            'id' => $id,
            'title' => $title,
            'address' => $address,
            'madrasa_id' => $teacher_madrasa_arr['madrasas']['id'],
            'madrasa' => $teacher_madrasa_arr['madrasas']['title'],
            'user_id' => $user_id,
            'status' => $status,
            'courses_count' => $courses_count,
            'subjects_count' => $subjects_count
        );
        array_push($teacher_arr['data'], $teacher);
                 
                            /* old way starts here */
            //     $teacher = array(
            //         'id' => $id,
            // 'title' => $title,
            // 'address' => $address,
            // 'madrasa_id' => $madrasa_id,
            // 'user_id' => $user_id,
            // 'status' => $status,
            // 'courses_count' => $courses_count,
            // 'subjects_count' => $subjects_count
            //     );

            //     array_push($teacher_arr['data'], $teacher);

                        /* old way ends here */
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

