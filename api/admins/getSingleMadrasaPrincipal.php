<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/principal.php';

$principal = new Principal($db);

$madrasa_id = isset($_GET['madrasa_id']) ?  $_GET['madrasa_id'] : 0;

if (isset($madrasa_id)) {

    if(empty($madrasa_id) or $madrasa_id == '0' ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid madrasa id ')
        );
    } else {

        $principal->madrasa_id = $madrasa_id;

        $result = $principal->getSinglePrincipalByMadrasaId();
        $row_count = $result->rowCount();
        

        $principal_arr = array();
        $principal_arr['data'] = array();

        if ($row_count > 0) {
            
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                 extract($row);
                $principal = array(
                    'id' => $id,
                    'title' => $title,
                    'address' => $address,
                    'madrasa_id' => $madrasa_id,
                    'status' => $status
                );
                array_push($principal_arr['data'], $principal);
            }
            
        } else {
            $principal = array(
                'id' => null,
                'title' => null,
                'address' => null,
                'madrasa_id' => null,
                'status' => null
            );
            array_push($principal_arr['data'], $principal);
        }
        

        echo json_encode($principal_arr);


    }


}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Madrasa ID')
    );
}



?>

