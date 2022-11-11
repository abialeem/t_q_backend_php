<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/jamaat.php';

$jamaat = new Jamaat($db);

$result = $jamaat->getAllJamaats();
$row_count = $result->rowCount();

if ($row_count > 0) {
    $jamaat_arr = array();
    $jamaat_arr['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $jamaat = array(
            'id' => $id,
            'jamaat' => $jamaat
           
            );
        array_push($jamaat_arr['data'], $jamaat);
    }
    echo json_encode($jamaat_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array('message' => 'No Jamaats to be found')
    );
}
?>

