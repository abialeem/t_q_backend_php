<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');



include_once('../../core/initialize.php');
include_once '../../models/admin.php';
include_once '../../models/madrasa.php';
include_once '../../models/principal.php';
include_once '../../models/teacher.php';
include_once '../../models/student.php';
include_once '../../models/course.php';
include_once '../../models/subject.php';
include_once '../../models/topic.php';
include_once '../../models/video.php';
include_once '../../models/quiz.php';

$admin = new Admin($db);

$madrasa = new Madrasa($db);

$principal = new Principal($db);

$teacher = new Teacher($db);

$student = new Student($db);

$course = new Course($db);

$subject = new Subject($db);

$topic = new Topic($db);

$video = new Video($db);

$quiz = new Quiz($db);


$type = isset($_GET['type']) ?  $_GET['type'] : 0;

if (isset($type)) {
    if(empty($type) or $type == '0' ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please select a type')
        );
    } else {

       if( $type == 'madrasa' ){

        $result = $madrasa->getAllMadrasas();
        $row_count = $result->rowCount();

       }
       elseif( $type == 'principal' ){

        $result = $principal->getAllPrincipals();
        $row_count = $result->rowCount();

       }
       elseif( $type == 'teacher' ){

        $result = $teacher->getAllTeachers();
        $row_count = $result->rowCount();

       }
       elseif( $type == 'student' ){

        $result = $student->getAllStudents();
        $row_count = $result->rowCount();

       }
       elseif( $type == 'course' ){

        $result = $course->getAllCourses();
        $row_count = $result->rowCount();

       }
       elseif( $type == 'subject' ){

        $result = $subject->getAllSubjects();
        $row_count = $result->rowCount();

       }
       elseif( $type == 'topic' ){

        $result = $topic->getAllTopics();
        $row_count = $result->rowCount();

       }
       elseif( $type == 'video' ){

        $result = $video->getAllVideos();
        $row_count = $result->rowCount();

       }
       elseif( $type == 'quiz' ){

        $result = $quiz->getAllQuizzes();
        $row_count = $result->rowCount();

       }
       else{

        $row_count = null;

       }
      
       
       echo json_encode($row_count);

    }


}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Type')
    );
}


?>