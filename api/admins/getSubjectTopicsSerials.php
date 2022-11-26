<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/topic.php';

$topic = new Topic($db);

$subject_id = isset($_GET['subject_id']) ?  $_GET['subject_id'] : 0;

$subject_id = isset($_GET['subject_id']) ?  $_GET['subject_id'] : 0;

if (isset($subject_id) && isset($subject_id)) {
    if( (empty($subject_id) or $subject_id == '0') && (empty($subject_id) or $subject_id == '0') ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid course ID and subject ID ')
        );
    } else {

$topic->subject_id = $subject_id;

$topic->subject_id = $subject_id;

$result = $topic->getAllTopicsSerialsBySubjectID();
$row_count = $result->rowCount();


$topic_arr = array();
$topic_arr['data'] = array();

if ($row_count > 0) {
    
    
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $topic =  $serial_no ;
        array_push($topic_arr['data'], $topic);
    }
    echo json_encode($topic_arr);
} else {
    array_push($topic_arr['data'], 0);
    echo json_encode($topic_arr);
}

    }

}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Course ID And A Subject ID')
    );
}


?>

