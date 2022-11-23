<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/jamaat.php';

$jamaat = new Jamaat($db);

$jamaat_id = isset($_GET['jamaat_id']) ?  $_GET['jamaat_id'] : 0;

if (isset($jamaat_id)) {
    if(empty($jamaat_id) or $jamaat_id == '0' ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid jamaat id ')
        );
    } else {

        $jamaat->id = $jamaat_id;

        $result = $jamaat->getJamaatTitle();
        $row_count = $result->rowCount();
        
        if ($row_count > 0) {
        $jamaat_row = $result->fetch(PDO::FETCH_ASSOC);
        extract($jamaat_row);
        $jamaat_arr = array(
            'jamaat' => $jamaat
        );

        echo json_encode( $jamaat_arr );
           
        } else {
            
            echo json_encode(
                array('message' => 'Provided Jamaat Not Found','jamaat' => 'null')
            );
        }

    }


}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Jamaat ID')
    );
}


?>

