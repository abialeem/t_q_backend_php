<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../../core/initialize.php');
include_once '../../../models/user.php';

$user = new User($db);


$email = isset($_GET['email']) ?  $_GET['email'] : 0;

if (isset($email)) {
    if(empty($email) or $email == '0' ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid email id')
        );
    } else {

//stuff to check email starts here

$user->email = $email;

$result = $user->isRegistered();
$row_count = $result->rowCount();

if ($row_count > 0) {
   
    echo json_encode(
        array('result' => '1' ,'message' => 'email id exists')
    );
} else {
    // http_response_code(404);
    echo json_encode(
        array('result' => '0' ,'message' => 'email id doesnt exists')
    );
}

//stuff to check email ends here


    }

}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide An Email Id')
    );
}

?>

