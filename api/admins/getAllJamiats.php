<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/jamiat.php';

$jamiat = new Jamiat($db);

$result = $jamiat->getAllJamiats();
$row_count = $result->rowCount();

if ($row_count > 0) {
    $jamiat_arr = array();
    $jamiat_arr['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $jamiat = array(
            'id' => $id,
            'jamiat' => $jamiat
            );
        array_push($jamiat_arr['data'], $jamiat);
    }
    echo json_encode($jamiat_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array('message' => 'No Jamiats to be found')
    );
}
?>

