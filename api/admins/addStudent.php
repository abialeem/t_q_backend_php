<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../../core/initialize.php';
include_once '../../models/student.php';
include_once '../../models/user.php';

$student = new Student($db);

$user = new User($db);


$data = json_decode(file_get_contents("php://input"));

if (isset($data->title) && isset($data->address) && isset($data->username) && isset($data->email) && isset($data->password) && isset($data->its_number)) {
    if (empty($data->title) || empty($data->address) || empty($data->username) || empty($data->password) || empty($data->email) || empty($data->its_number) ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please enter all the required fields to add a student')
        );
    } else {
        $student->title = $data->title;
        $student->address = $data->address;
        $student->its_number = $data->its_number;
        $user->username = $data->username;
        $user->email = $data->email;
        $user->password = $data->password;
        $user->user_type = 'student';
            $result = $user->isRegistered();
            $result_std = $student->isRegistered();
            $row_count = $result->rowCount();
            $row_count_std = $result_std->rowCount();
            if ($row_count > 0 || $row_count_std > 0) {
                http_response_code(422);
                echo json_encode(
                    array('message' => 'User Already Exists. Please try Valid New Details')
                );
            } else {
                        //add the user then from insert id ...add teacher
                    $result =  $user->registerUser();
                    $row_count = $result->rowCount();
                if ($row_count > 0) {
                        
                    //get last insert id and add teacher now
                    $last_id = $db->lastInsertId();

                    //now add the teacher
                    $student->user_id = $last_id;
                    $student->madrasa_id = 0;
                    $student->course_id = 0;
                    $student->status = 1;
                            
                    if ($student->addStudent()) {
                        http_response_code(201);
                        echo json_encode(
                            array('message' => 'Student Registered successfully')
                        );
                    } else {
                                //on failure ...revert back the previous user added ...delete that user

                        http_response_code(500);
                        echo json_encode(
                            array('message' => 'student could not be registered step2-error')
                        );
                    }

                } else {
                    http_response_code(500);
                    echo json_encode(
                        array('message' => 'student could not be registered.....user addition error')
                    );
                }

            }
        
    }
} else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please enter all the required fields')
    );
}
?>