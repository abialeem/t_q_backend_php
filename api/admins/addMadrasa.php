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

$madrasa= new Madrasa($db);

$data = json_decode(file_get_contents("php://input"));

if (isset($data->madrasa_title) && isset($data->madrasa_address) && isset($data->madrasa_jamiat_id) && isset($data->madrasa_jamaat_id)) {
    if (empty($data->madrasa_title) || empty($data->madrasa_address) || empty($data->madrasa_jamiat_id) || empty($data->madrasa_jamaat_id)) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please enter all the required fields to add a madrasa')
        );
    } else {
        $madrasa->madrasa_title = $data->madrasa_title;
        $madrasa->madrasa_address = $data->madrasa_address;
        $madrasa->madrasa_jamiat_id = $data->madrasa_jamiat_id;
        $madrasa->madrasa_jamaat_id = $data->madrasa_jamaat_id;
        
       
            $result = $madrasa->madrasaExists();
            $row_count = $result->rowCount();
            if ($row_count > 0) {
                http_response_code(422);
                echo json_encode(
                    array('message' => 'Madrasa Already Exists. Please try Valid New Madrasa Details')
                );
            } else {
                if ($madrasa->addMadrasa()) {
                    http_response_code(201);
                    echo json_encode(
                        array('message' => 'Madrasa Registered successfully')
                    );
                } else {
                    http_response_code(500);
                    echo json_encode(
                        array('message' => 'Madrasa could not be registered')
                    );
                }
            }
        
    }
} else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please enter all the required fields')
    );
}
?>