<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/principal.php';

$principal = new Principal($db);

$principal_id = isset($_GET['principal_id']) ?  $_GET['principal_id'] : 0;

if (isset($principal_id)) {
    if(empty($principal_id) or $principal_id == '0' or $principal_id == null or $principal_id == "null" ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid principal id ')
        );
    } else {

        $principal->id = $principal_id;

        $result = $principal->getSinglePrincipalById();
        $row_count = $result->rowCount();
        
        if ($row_count > 0) {
            $principal_arr = array();
            $principal_arr['data'] = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                 extract($row);
                $principal = array(
                    'id' => $id,
                    'title' => $title,
                    'address' => $address,
                    'user_id' => $user_id,
                    'madrasa_id' => $madrasa_id,
                    'status' => $status
                );
                array_push($principal_arr['data'], $principal);
            }
            echo json_encode($principal_arr);
        } else {
            http_response_code(404);
            echo json_encode(
                array('message' => 'No principal to be found')
            );
        }
    }
}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A principal ID')
    );
}
?>

