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

if (isset($data->id) && isset($data->madrasa_id) ) {
    if (empty($data->id) or empty($data->madrasa_id) ) {

        http_response_code(422);
        echo json_encode(
            array('message' => 'Please enter all the required fields to unassign course of madrasa')
        );

    } else {
        
        $madrasa_courses->madrasa_id = $data->madrasa_id;

        $madrasa_courses->course_id = $data->id;


        //check if entry of this course with this madrasa exists
        $result_check = $madrasa_courses->courseAssignedToMadrasa();

        $check_row_count = $result_check->rowCount();
                if($check_row_count < 0)
                 {
                    http_response_code(201);
                    echo json_encode(
                        array('message' => 'Course is not assigned to this madrasa.')
                    );
        
                 }
                else{
                            //course exists for this madrasa ....so now unassign it
                    
                            if($madrasa_courses->unassignCourseFromMadrasa())
                                     {      //if block for unassignCourseFromMadrasa starts here
                                                http_response_code(201);
                                                echo json_encode(
                                                        array('message' => 'course Unassigned From Madrasa successfully')
                                                                );
                                           
                                    } else {  // else block starts  for unassignCourseFromMadrasa  here
                                                    http_response_code(500);
                                                    echo json_encode(
                                                        array('message' => 'course could not be unassigned.')
                                                    );
                                    }       // else block ends  for unassignCourseFromMadrasa  here

                    }
    }
} else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please enter all the required fields')
    );
}
?>