<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/quiz.php';

$quiz = new Quiz($db);

$topic_id = isset($_GET['topic_id']) ?  $_GET['topic_id'] : 0;

if (isset($topic_id)) {
    if( empty($topic_id) or $topic_id == '0' ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid  topic ID ')
        );
    } else {

$quiz->topic_id = $topic_id;

$result = $quiz->getAllQuizzesSerialsByTopicID();
$row_count = $result->rowCount();


$quiz_arr = array();
$quiz_arr['data'] = array();

if ($row_count > 0) {
    
    
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $quiz =  $serial_no ;
        array_push($quiz_arr['data'], $quiz);
    }
    echo json_encode($quiz_arr);
} else {
    array_push($quiz_arr['data'], 0);
    echo json_encode($quiz_arr);
}



    }

}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide  A Topic ID')
    );
}


?>

