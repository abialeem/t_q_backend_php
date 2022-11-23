<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/principal.php';

$principal = new Principal($db);

$result = $principal->getUnassignedPrincipals();
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
            'madrasa_id' => $madrasa_id,
            'status' => $status
        );
        array_push($principal_arr['data'], $principal);
    }
    echo json_encode($principal_arr);
} else {
    // http_response_code(404);
    echo json_encode(
        array('message' => 'No principals to be found')
    );
}
?>

