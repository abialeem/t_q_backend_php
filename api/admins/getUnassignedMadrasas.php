<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/madrasa.php';
include_once '../../models/teacherMadrasas.php';

$madrasa = new Madrasa($db);

$all_madrasas = new Madrasa($db);

$teacher_madrasas = new TeacherMadrasas($db);


$teacher_id = isset($_GET['teacher_id']) ?  $_GET['teacher_id'] : 0;

if (isset($teacher_id)) {
    if(empty($teacher_id) or $teacher_id == '0' ) {
        //http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid teacher id ')
        );
    } else {
                    $madrasa_arr= array();
                    $madrasa_arr['data'] = array();
                    //get madrasa ids from a teacher id in teacher_madrasas
                    $teacher_madrasas_arr = array();
                    $teacher_madrasas_arr['madrasas']['id'] = array();
        //now add teacher id to teacher_madrasas
          $teacher_madrasas->teacher_id = $teacher_id;

        //now call getUnassignedTeachers from teacher_madrasas model
        $madrasas_of_teacher_result = $teacher_madrasas->getSingleTeacherMadrasas();
        while( $teacher_madrasa_row = $madrasas_of_teacher_result->fetch(PDO::FETCH_ASSOC)){
            //get teacher ids here
     array_push($teacher_madrasas_arr['madrasas']['id'], $teacher_madrasa_row['madrasa_id']);
            }

            // echo json_encode($teacher_madrasas_arr['madrasas']['id']);

            $result_all = $all_madrasas->getAllMadrasas();
            $row_count = $result_all->rowCount();
            if ($row_count > 0) {
                while ($row = $result_all->fetch(PDO::FETCH_ASSOC)) {
                          extract($row);
                          // echo $row['id'];
                          if (!in_array($row['id'], $teacher_madrasas_arr['madrasas']['id']))
                          {
                            // echo "not found";
                            array_push($madrasa_arr['data'], $row);
                          }

                }
               
            }
            
            echo json_encode($madrasa_arr);

    }
}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Teacher ID')
    );
}
                      
?>

