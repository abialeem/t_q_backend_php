<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../../core/initialize.php');
include_once '../../../models/student.php';

$student = new Student($db);


$its_number = isset($_GET['its_number']) ?  $_GET['its_number'] : 0;

if (isset($its_number)) {
    if(empty($its_number) or $its_number == '0' ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid its_number ')
        );
    } else {

//stuff to check its_number starts here

$student->its_number = $its_number;

$result = $student->isRegistered();
$row_count = $result->rowCount();

if ($row_count > 0) {
   
    echo json_encode(
        array('result' => '1' ,'message' => 'its_number exists')
    );
} else {
    // http_response_code(404);
    echo json_encode(
        array('result' => '0' ,'message' => 'its_number doesnt exists')
    );
}

//stuff to check its_number ends here


    }

}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide An ITS Number')
    );
}

?>

