<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/madrasa.php';
include_once '../../models/jamaat.php';
include_once '../../models/jamiat.php';
include_once '../../models/principal.php';

$madrasa = new Madrasa($db);

$jamaat = new Jamaat($db);

$jamiat = new Jamiat($db);

$principal = new Principal($db);

$result = $madrasa->getAllMadrasas();
$row_count = $result->rowCount();

if ($row_count > 0) {
    $madrasa_arr = array();
    $madrasa_arr['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
         extract($row);

        //get jamaat and jamiat titles
        $jamaat->id = $madrasa_jamaat_id;
        $jamiat->id = $madrasa_jamiat_id;
        $principal->madrasa_id = $row['id'];
        $result2 = $jamaat->getJamaatTitle();
        $result3 = $jamiat->getJamiatTitle();
        $result4 = $principal->getSinglePrincipalTitleByMadrasaId();
        $jamaat_row = $result2->fetch(PDO::FETCH_ASSOC);
        // extract($jamaat_row);
        $jamiat_row = $result3->fetch(PDO::FETCH_ASSOC);
        // extract($jamiat_row);
        $principal_row = $result4->fetch(PDO::FETCH_ASSOC);
        if($principal_row==null){
                $principal_row['title'] = "not assigned yet";
        }
        // extract($principal_row);
        $jamaat_title = ' ';
        $jamiat_title = ' ';
        $madrasa = array(
            'id' => $id,
            'madrasa_title' => $madrasa_title,
            'madrasa_address' => $madrasa_address,
            'madrasa_student_count' => $madrasa_student_count,
            'madrasa_teacher_count' => $madrasa_teacher_count,
            'madrasa_jamiat_id' => $madrasa_jamiat_id,
            'jamaat' => $jamaat_row['jamaat'],
            'jamiat' => $jamiat_row['jamiat'],
            'principal' => $principal_row['title'],
            'madrasa_jamaat_id' => $madrasa_jamaat_id,
            'status' => $status
        );

        array_push($madrasa_arr['data'], $madrasa);
       
        
        
    }
    echo json_encode($madrasa_arr);
} else {
   // http_response_code(404);
    echo json_encode(
        array('message' => 'No madrasa to be found')
    );
}
?>

