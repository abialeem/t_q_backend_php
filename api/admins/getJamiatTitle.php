<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/jamiat.php';

$jamiat = new Jamiat($db);

$jamiat_id = isset($_GET['jamiat_id']) ?  $_GET['jamiat_id'] : 0;

if (isset($jamiat_id)) {
    if(empty($jamiat_id) or $jamiat_id == '0' ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid jamiat id ')
        );
    } else {

        $jamiat->id = $jamiat_id;

        $result = $jamiat->getjamiatTitle();
        $row_count = $result->rowCount();
        
        if ($row_count > 0) {
        $jamiat_row = $result->fetch(PDO::FETCH_ASSOC);
        extract($jamiat_row);
        $jamiat_arr = array(
            'jamiat' => $jamiat
        );

        echo json_encode( $jamiat_arr );
           
        } else {
            
            echo json_encode(
                array('message' => 'Provided Jamiat Not Found','jamiat' => 'null')
            );
        }

    }


}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Jamiat ID')
    );
}


?>

