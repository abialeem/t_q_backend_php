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
include_once '../../models/principal.php';

$principal = new Principal($db);

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && isset($data->madrasa_id) ) {
    if (empty($data->id) || empty($data->madrasa_id)  ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please enter all the required fields to update principal of madrasa')
        );
    } else {
        
        $principal->id = $data->id;
        $principal->madrasa_id = $data->madrasa_id;

        //check if principal is unassigned
        $result_check = $principal->isPrincipalUnassigned();
        $check_row_count = $result_check->rowCount();
        if($check_row_count > 0)
        {
            if($principal->assignPrincipalToMadrasa())
            {
               http_response_code(201);
               echo json_encode(
                       array('message' => 'Principal Assigned To Madrasa successfully')
                               );
   
           } else {
                           http_response_code(500);
                           echo json_encode(
                               array('message' => 'Principal could not be assigned.')
                           );
           }
        }
        else{
            http_response_code(201);
            echo json_encode(
                array('message' => 'Principal already assigned to a madrasa.')
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