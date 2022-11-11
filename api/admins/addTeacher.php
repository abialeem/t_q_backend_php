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
include_once '../../models/teacher.php';
include_once '../../models/user.php';

$teacher = new Teacher($db);

$user = new User($db);


$data = json_decode(file_get_contents("php://input"));

if (isset($data->title) && isset($data->address) && isset($data->username) && isset($data->email) && isset($data->password)) {
    if (empty($data->title) || empty($data->address) || empty($data->username) || empty($data->password) || empty($data->email) ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please enter all the required fields to add a teacher')
        );
    } else {
        $teacher->title = $data->title;
        $teacher->address = $data->address;
        $user->username = $data->username;
        $user->email = $data->email;
        $user->password = $data->password;
        $user->user_type = 'teacher';
            $result = $user->isRegistered();
            $row_count = $result->rowCount();
            if ($row_count > 0) {
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
                    $teacher->user_id = $last_id;
                    $teacher->madrasa_id = 0;
                    $teacher->courses_count = 0;
                    $teacher->subjects_count = 0;
                    $teacher->status = 1;

                    if ($teacher->addTeacher()) {
                        http_response_code(201);
                        echo json_encode(
                            array('message' => 'teacher Registered successfully')
                        );
                    } else {
                                //on failure ...revert back the previous user added ...delete that user

                        http_response_code(500);
                        echo json_encode(
                            array('message' => 'teacher could not be registered step2-error')
                        );
                    }






                } else {
                    http_response_code(500);
                    echo json_encode(
                        array('message' => 'teacher could not be registered.....user addition error')
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