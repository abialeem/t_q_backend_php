<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/teacher.php';
include_once '../../models/teacherMadrasas.php';

$teacher = new Teacher($db);

$teacher_madrasas = new TeacherMadrasas($db);

$madrasa_id = isset($_GET['madrasa_id']) ?  $_GET['madrasa_id'] : 0; 

if (isset($madrasa_id)) {

    if(empty($madrasa_id) or $madrasa_id == '0' ) {
        //http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid madrasa id ')
        );
    } else {
                            $teacher_arr= array();
                            $teacher_arr['data'] = array();
                    //get teacher ids from a madrasa id in teacher_madrasas
                    $madrasa_teachers_arr = array();
                    $madrasa_teachers_arr['teachers']['id'] = array();
                    $teacher_madrasas->madrasa_id = $madrasa_id;
                    $teacher_madrasa_result = $teacher_madrasas->getSingleMadrasaTeachers();
                    while( $teacher_madrasa_row = $teacher_madrasa_result->fetch(PDO::FETCH_ASSOC)){
                                //get teacher ids here
                         array_push($madrasa_teachers_arr['teachers']['id'], $teacher_madrasa_row['teacher_id']);
                    }

                    //echo json_encode($madrasa_teachers_arr);
                    
                    foreach ($madrasa_teachers_arr['teachers']['id'] as $madrasa_teacher_id) {
                                    //get teacher data by id 
                                    $teacher->id = $madrasa_teacher_id;
                                    $result = $teacher->getSingleTeacherById();
                                    $row = $result->fetch(PDO::FETCH_ASSOC);
                                    $row['madrasa_id'] = $madrasa_id;
                                    //         $teacher = array(
                                    //             'id' => $row['id'],
                                    //             'title' => $row['title'],
                                    //             'madrasa_id' => $row['madrasa_id'],
                                    //             'address' => $row['address'],
                                    //             'courses_count' => $row['courses_count'],
                                    //             'subjects_count' => $row['subjects_count'],
                                    //             'status' => $row['status']
                                    //         );
                                            
                                            array_push($teacher_arr['data'], $row);
                                           
                                  
                                            
                      }
                      
                     
                      echo json_encode($teacher_arr);
    }


}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Madrasa ID')
    );
}



?>

