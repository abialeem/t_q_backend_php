<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');



include_once('../../core/initialize.php');
include_once '../../models/admin.php';

$user = new Admin($db);

// $data =  new stdClass();

$data = json_decode(file_get_contents("php://input"));

// if (isset($_POST['email']) && isset($_POST['password'])) {
if (isset($data->email) && isset($data->password)) {
    // $data->email = $_POST['email'];

    // $data->password = $_POST['password'];


    if (empty($data->email) || empty($data->password)) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please enter valid  required fields')
        );
    } else {
        $user->email = $data->email;
        $user->password = $data->password;
        if ($user->isLoggedIn()) {
            http_response_code(400);
            echo json_encode(
                array('message' => 'You are currently logged in.')
            );
        } else {
            if (!$user->isEmailValid()) {
                http_response_code(422);
                echo json_encode(
                    array('message' => 'Please enter a valid email address')
                );
            } else {
                $result = $user->loginAdmin();
                $row_count = $result->rowCount();
                if ($row_count > 0) {
                    $user_arr = array();
                    $user_arr['data'] = array();
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    extract($row);
                    // $_SESSION['id'] = $id;

                    //after logging in user stuff to send starts here
                    $user = array(
                        'username' => $username,
                        'email' => $email,
                        'id' => $adminid
                       
                    );
                    array_push($user_arr['data'], $user);


                    http_response_code(202);
                    echo json_encode(
                        $user_arr
                    );


                     //after logging in user stuff to send ends here

                 

                } else {
                    http_response_code(422);
                    echo json_encode(
                        array('message' => 'Could not find that email address. Please register first.')
                    );
                }
            }
        }
    }
} else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please enter all the required fields ')
    );
}


?>