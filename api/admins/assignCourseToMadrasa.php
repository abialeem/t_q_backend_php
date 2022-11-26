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
include_once '../../models/madrasaCourses.php';

$madrasa_courses = new MadrasaCourses($db);


$data = json_decode(file_get_contents("php://input"));

if (isset($data->course_id) && isset($data->madrasa_id) ) {
    if (empty($data->course_id) or empty($data->madrasa_id) ) {

        http_response_code(422);
        echo json_encode(
            array('message' => 'Please enter all the required fields to assign course to madrasa')
        );

    } else {
        
        $madrasa_courses->madrasa_id = $data->madrasa_id;

        $madrasa_courses->course_id = $data->course_id;


          //check if entry of this course with this madrasa exists
          $result_check = $madrasa_courses->courseAssignedToMadrasa();

          $check_row_count = $result_check->rowCount();
                  if($check_row_count > 0)
                   {
                      http_response_code(201);
                      echo json_encode(
                          array('message' => 'course already assigned to this Madrasa.')
                      );
          
                   }
                  else{


                        //course doesnt exists for this madrasa ....so now assign it
                    
                            if($madrasa_courses->assignCourseToMadrasa())
                                     {      //if block for assignCourseToMadrasa starts here

                                                http_response_code(201);
                                                echo json_encode(
                                                        array('message' => 'course assigned to Madrasa successfully')
                                                                );

                                    } else {  // else block starts  for assignCourseToMadrasa  here
                                                    http_response_code(500);
                                                    echo json_encode(
                                                        array('message' => 'course could not be assigned.')
                                                    );
                                    }       // else block ends  for assignCourseToMadrasa  here



                  }
                      
    }
} else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please enter all the required fields')
    );
}
?>