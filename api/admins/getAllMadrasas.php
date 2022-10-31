<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/madrasa.php';

$madrasa = new Madrasa($db);

$result = $madrasa->getAllMadrasas();
$row_count = $result->rowCount();

if ($row_count > 0) {
    $madrasa_arr = array();
    $madrasa_arr['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $madrasa = array(
            'id' => $id,
            'madrasa_title' => $madrasa_title,
            'madrasa_address' => $madrasa_address,
            'madrasa_student_count' => $madrasa_student_count,
            'madrasa_teacher_count' => $madrasa_teacher_count,
            'madrasa_jamiat_id' => $madrasa_jamiat_id,
            'madrasa_jamaat_id' => $madrasa_jamaat_id,
            'status' => $status
        );
        array_push($madrasa_arr['data'], $madrasa);
    }
    echo json_encode($madrasa_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array('message' => 'No madrasa to be found')
    );
}
?>

