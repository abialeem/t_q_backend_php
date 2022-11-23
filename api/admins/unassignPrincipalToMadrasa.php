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

if (isset($data->id) ) {
    if (empty($data->id)  ) {
        // http_response_code(422);
        // echo json_encode(
        //     array('message' => 'Please enter all the required fields to unassign principal of madrasa')
        // );
 
        //empty id means no previous principal was assigned so for now ignore this call
        http_response_code(201);
        echo json_encode(
            array('message' => 'Check Complete...No Need To Unassign Principal  From Madrasa ')
                    );
    } else {
        
        $principal->id = $data->id;
        
        //check if principal is already unassigned
        $result_check = $principal->isPrincipalUnassigned();
        $check_row_count = $result_check->rowCount();
        if($check_row_count > 0)
        {
            http_response_code(201);
            echo json_encode(
                array('message' => 'Principal already unassigned.')
            );

        }
        else{
            if($principal->unassignPrincipalFromMadrasa())
            {
               http_response_code(201);
               echo json_encode(
                       array('message' => 'Principal Unassigned From Madrasa successfully')
                               );
   
           } else {
                           http_response_code(500);
                           echo json_encode(
                               array('message' => 'Principal could not be unassigned.')
                           );
           }
        }
    }
} else {
     //empty id means no previous principal was assigned so for now ignore this call
    http_response_code(201);
    echo json_encode(
        array('message' => 'Check Complete...No Need To Unassign Principal  From Madrasa ')
                );
    // echo json_encode(
    //     array('message' => 'Please enter all the required fields')
    // );
}
?>