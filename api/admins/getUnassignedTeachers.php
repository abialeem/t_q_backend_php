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

$all_teachers = new Teacher($db);

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
        //now add madrasa id to teacher_madrasas
          $teacher_madrasas->madrasa_id = $madrasa_id;

        //now call getUnassignedTeachers from teacher_madrasas model
        $teachers_of_madrasa_result = $teacher_madrasas->getSingleMadrasaTeachers();
        while( $teacher_madrasa_row = $teachers_of_madrasa_result->fetch(PDO::FETCH_ASSOC)){
            //get teacher ids here
     array_push($madrasa_teachers_arr['teachers']['id'], $teacher_madrasa_row['teacher_id']);
            }

            // echo json_encode($madrasa_teachers_arr['teachers']['id']);

            $result_all = $all_teachers->getAllTeachers();
            $row_count = $result_all->rowCount();
            if ($row_count > 0) {
                while ($row = $result_all->fetch(PDO::FETCH_ASSOC)) {
                          extract($row);
                          // echo $row['id'];
                          if (!in_array($row['id'], $madrasa_teachers_arr['teachers']['id']))
                          {
                            // echo "not found";
                            array_push($teacher_arr['data'], $row);
                          }

                }
               
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
                        //old way of getting unassigned teacher starts here
// $result = $teacher->getUnassignedTeachers();
// $row_count = $result->rowCount();

// if ($row_count > 0) {
//     $teacher_arr = array();
//     $teacher_arr['data'] = array();
//     while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
//         extract($row);
//         $teacher = array(
//             'id' => $id,
//             'title' => $title,
//             'address' => $address,
//             'madrasa_id' => $madrasa_id,
//             'status' => $status,
//             'courses_count' => $courses_count,
//             'subjects_count' => $subjects_count
//         );
//         array_push($teacher_arr['data'], $teacher);
//     }
//     echo json_encode($teacher_arr);
// } else {
//     // http_response_code(404);
//     echo json_encode(
//         array('message' => 'No teachers to be found')
//     );
// }                        
                         //old way of getting unassigned teacher ends here
?>

