<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/course.php';
include_once '../../models/madrasaCourses.php';

$madrasa_courses = new MadrasaCourses($db);

$course = new Course($db);

$madrasa_id = isset($_GET['madrasa_id']) ?  $_GET['madrasa_id'] : 0; 

if (isset($madrasa_id)) {

    if(empty($madrasa_id) or $madrasa_id == '0' ) {
        //http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid madrasa id ')
        );
    } else {
                            $course_arr= array();
                            $course_arr['data'] = array();
                    //get course ids from a madrasa id in madrasa_courses
                    $madrasa_courses_arr = array();
                    $madrasa_courses_arr['courses']['id'] = array();
                    $madrasa_courses->madrasa_id = $madrasa_id;
                    $course_madrasa_result = $madrasa_courses->getSingleMadrasaCourses();
                    while( $course_madrasa_row = $course_madrasa_result->fetch(PDO::FETCH_ASSOC)){
                                //get course ids here
                         array_push($madrasa_courses_arr['courses']['id'], $course_madrasa_row['course_id']);
                    }

                    //echo json_encode($madrasa_courses_arr);
                    
                    foreach ($madrasa_courses_arr['courses']['id'] as $madrasa_course_id) {
                                    //get course data by id 
                                    $course->id = $madrasa_course_id;
                                    $result = $course->getSingleCourseById();
                                    $row = $result->fetch(PDO::FETCH_ASSOC);
                                    //$row['madrasa_id'] = $madrasa_id;
                                            
                                            array_push($course_arr['data'], $row);
                                           
                      }
                      
                      echo json_encode($course_arr);
    }


}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Madrasa ID')
    );
}



?>

