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
include_once '../../models/teacherMadrasas.php';
include_once '../../models/madrasa.php';

$teacher_madrasas = new TeacherMadrasas($db);

$madrasa = new Madrasa($db);

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && isset($data->madrasa_id) ) {
    if (empty($data->id) or empty($data->madrasa_id) ) {

        http_response_code(422);
        echo json_encode(
            array('message' => 'Please enter all the required fields to assign teacher of madrasa')
        );

    } else {
        
        $madrasa->id = $data->madrasa_id;

        $teacher_madrasas->madrasa_id = $data->madrasa_id;

        $teacher_madrasas->teacher_id = $data->id;


          //check if entry of this teacher with this madrasa exists
          $result_check = $teacher_madrasas->teacherAssignedToMadrasa();

          $check_row_count = $result_check->rowCount();
                  if($check_row_count > 0)
                   {
                      http_response_code(201);
                      echo json_encode(
                          array('message' => 'teacher already assigned to this Madrasa.')
                      );
          
                   }
                  else{


                        //teacher doesnt exists for this madrasa ....so now assign it
                    
                            if($teacher_madrasas->assignTeacherToMadrasa())
                                     {      //if block for assignTeacherToMadrasa starts here
                                            //decrement teacher count of given madrasa
                                            if($madrasa->incrementTeacherCount())
                                            {
                                                http_response_code(201);
                                                echo json_encode(
                                                        array('message' => 'teacher assigned to Madrasa successfully')
                                                                );
                                            }
                                            else{
                                                http_response_code(201);
                                                    echo json_encode( 
                                                        array('message' => 'teacher  assigned but error in teacher count of madrasa.')
                                                    );
                                            }
                            
                                    } else {  // else block starts  for assignTeacherToMadrasa  here
                                                    http_response_code(500);
                                                    echo json_encode(
                                                        array('message' => 'teacher could not be assigned.')
                                                    );
                                    }       // else block ends  for assignTeacherToMadrasa  here



                  }
                             /* .... 

                                old way to assign teacher to madrasa starts here

                                ......
                                 */

        // $teacher->id = $data->id;
        // $teacher->madrasa_id = $data->madrasa_id;

        // $madrasa->id = $data->madrasa_id;
        
        // //check if teacher is  unassigned
        // $result_check = $teacher->isTeacherUnassigned();
        // $check_row_count = $result_check->rowCount();
        // if($check_row_count > 0)
        //  {
        //     if($teacher->assignTeacherToMadrasa())
        //     {
        //            //increment teacher count of given madrasa
        //            if($madrasa->incrementTeacherCount())
        //            {
        //                http_response_code(201);
        //                echo json_encode(
        //                        array('message' => 'teacher assigned to Madrasa successfully')
        //                                );
        //            }
        //            else{
        //                http_response_code(201);
        //                    echo json_encode(
        //                        array('message' => 'teacher  assigned but error in teacher count of madrasa.')
        //                    );
        //            }
   
        //    } else {
        //                    http_response_code(500);
        //                    echo json_encode(
        //                        array('message' => 'teacher could not be assigned.')
        //                    );
        //    }
        //  }
        //  else
        //  {
        //     http_response_code(201);
        //     echo json_encode(
        //             array('message' => 'teacher already assigned to a Madrasa ')
        //                     );
        //  }              

                                /* .... 

                                old way to assign teacher to madrasa ends here

                                ......
                                 */
    }
} else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please enter all the required fields')
    );
}
?>