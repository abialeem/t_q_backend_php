<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../../core/initialize.php';
include_once '../../models/madrasa.php';
include_once '../../models/teacherMadrasas.php';


$madrasa = new Madrasa($db);

$teacher_madrasas = new TeacherMadrasas($db);

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && isset($data->madrasa_id) ) {
    if (empty($data->id) or empty($data->madrasa_id) ) {

        http_response_code(422);
        echo json_encode(
            array('message' => 'Please enter all the required fields to unassign teacher of madrasa')
        );

    } else {
        
        $madrasa->id = $data->madrasa_id;

        $teacher_madrasas->madrasa_id = $data->madrasa_id;

        $teacher_madrasas->teacher_id = $data->id;


        //check if entry of this teacher with this madrasa exists
        $result_check = $teacher_madrasas->teacherAssignedToMadrasa();

        $check_row_count = $result_check->rowCount();
                if($check_row_count < 0)
                 {
                    http_response_code(201);
                    echo json_encode(
                        array('message' => 'Teacher is not assigned to this madrasa.')
                    );
        
                 }
                else{
                            //teacher exists for this madrasa ....so now unassign it
                    
                            if($teacher_madrasas->unassignTeacherFromMadrasa())
                                     {      //if block for unassignTeacherFromMadrasa starts here
                                            //decrement teacher count of given madrasa
                                            if($madrasa->decrementTeacherCount())
                                            {
                                                http_response_code(201);
                                                echo json_encode(
                                                        array('message' => 'teacher Unassigned From Madrasa successfully')
                                                                );
                                            }
                                            else{
                                                http_response_code(201);
                                                    echo json_encode( 
                                                        array('message' => 'teacher  unassigned but error in teacher count of madrasa.')
                                                    );
                                            }
                            
                                    } else {  // else block starts  for unassignTeacherFromMadrasa  here
                                                    http_response_code(500);
                                                    echo json_encode(
                                                        array('message' => 'teacher could not be unassigned.')
                                                    );
                                    }       // else block ends  for unassignTeacherFromMadrasa  here


                    }
        //old way of unassigning a teacher starts here before version 3.3

//         $teacher->id = $data->id;

//         $madrasa->id = $data->madrasa_id;
        
//   //check if teacher is already unassigned
//         $result_check = $teacher->isTeacherUnassigned();
//         $check_row_count = $result_check->rowCount();
//         if($check_row_count > 0)
//          {      //row count for isTeacherUnassigned starts here
//             http_response_code(201);
//             echo json_encode(
//                 array('message' => 'Teacher already unassigned.')
//             );

//          }      //row count for isTeacherUnassigned ends here
//         else{       //else block for row count for isTeacherUnassigned starts here 

//                     //teacher is not already unassigned so unassign it
//                     if($teacher->unassignTeacherFromMadrasa())
//          {      //if block for unassignTeacherFromMadrasa starts here
//                 //decrement teacher count of given madrasa
//                 if($madrasa->decrementTeacherCount())
//                 {
//                     http_response_code(201);
//                     echo json_encode(
//                             array('message' => 'teacher Unassigned From Madrasa successfully')
//                                     );
//                 }
//                 else{
//                     http_response_code(201);
//                         echo json_encode( 
//                             array('message' => 'teacher  unassigned but error in teacher count of madrasa.')
//                         );
//                 }

//         } else {  // else block starts  for unassignTeacherFromMadrasa  here
//                         http_response_code(500);
//                         echo json_encode(
//                             array('message' => 'teacher could not be unassigned.')
//                         );
//         }       // else block ends  for unassignTeacherFromMadrasa  here

//         }  //else block for row count for isTeacherUnassigned ends here 

        //old way of unassigning a teacher ends here before version 3.3
        
    }
} else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please enter all the required fields')
    );
}
?>