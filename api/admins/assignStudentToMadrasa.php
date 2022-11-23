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
include_once '../../models/student.php';
include_once '../../models/madrasa.php';

$student = new Student($db);

$madrasa = new Madrasa($db);

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && isset($data->madrasa_id) ) {
    if (empty($data->id) or empty($data->madrasa_id) ) {

        http_response_code(422);
        echo json_encode(
            array('message' => 'Please enter all the required fields to assign student of madrasa')
        );

    } else {
        
        $student->id = $data->id;
        $student->madrasa_id = $data->madrasa_id;

        $madrasa->id = $data->madrasa_id;
        

        //check if student is  unassigned
        $result_check = $student->isStudentUnassigned();
        $check_row_count = $result_check->rowCount();
        if($check_row_count > 0)
         {
            if($student->assignStudentToMadrasa())
         {
                //increment student count of given madrasa
                if($madrasa->incrementStudentCount())
                {
                    http_response_code(201);
                    echo json_encode(
                            array('message' => 'student assigned to Madrasa successfully')
                                    );
                }
                else{
                    http_response_code(201);
                        echo json_encode(
                            array('message' => 'student  assigned but error in student count of madrasa.')
                        );
                }

        } else {
                        http_response_code(500);
                        echo json_encode(
                            array('message' => 'student could not be assigned.')
                        );
        }
         }
         else
         {
            http_response_code(201);
            echo json_encode(
                array('message' => 'Student already assigned to a madrasa . ')
            );
         }
    }
} else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please enter all the required fields')
    );
}
?>