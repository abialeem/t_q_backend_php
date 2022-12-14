<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/principal.php';
include_once '../../models/madrasa.php';

$principal = new Principal($db);

$madrasa = new Madrasa($db);

$result = $principal->getAllPrincipals();
$row_count = $result->rowCount();

if ($row_count > 0) {
    $principal_arr = array();
    $principal_arr['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $madrasa->id = $row['madrasa_id'];

        $result2 = $madrasa->getSingleMadrasaById();
        $madrasa_row = $result2->fetch(PDO::FETCH_ASSOC);
        if($madrasa_row==null){
                $madrasa_row['madrasa_title'] = "not assigned yet";
        }

        $principal = array(
            'id' => $id,
            'title' => $title,
            'address' => $address,
            'madrasa' => $madrasa_row['madrasa_title'],
            'madrasa_id' => $madrasa_id,
            'status' => $status
        );
        array_push($principal_arr['data'], $principal);
    }
    echo json_encode($principal_arr);
} else {
    //http_response_code(404);
    echo json_encode(
        array('message' => 'No principals to be found')
    );
}
?>

