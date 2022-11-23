<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/user.php';

$user = new User($db);

$user_id = isset($_GET['user_id']) ?  $_GET['user_id'] : 0;

if (isset($user_id)) {
    if(empty($user_id) or $user_id == '0' or $user_id == null or $user_id == "null" ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid user id ')
        );
    } else {

        $user->userid = $user_id;

        $result = $user->getSingleUserById();
        $row_count = $result->rowCount();
        
        if ($row_count > 0) {
            $user_arr = array();
            $user_arr['data'] = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                 extract($row);
                $user = array(
                    'userid' => $userid,
                    'username' => $username,
                    'email' => $email,
                    'user_type' => $user_type,
                );
                array_push($user_arr['data'], $user);
            }
            echo json_encode($user_arr);
        } else {
            http_response_code(404);
            echo json_encode(
                array('message' => 'No user to be found')
            );
        }
    }
}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A User ID')
    );
}
?>

