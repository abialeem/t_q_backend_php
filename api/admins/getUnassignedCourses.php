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

$all_courses = new Course($db);

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
        //now add madrasa id to madrasa_courses
          $madrasa_courses->madrasa_id = $madrasa_id;

        //now call getUnassignedcourses from madrasa_courses model
        $courses_of_madrasa_result = $madrasa_courses->getSingleMadrasaCourses();
        while( $course_madrasa_row = $courses_of_madrasa_result->fetch(PDO::FETCH_ASSOC)){
            //get course ids here
     array_push($madrasa_courses_arr['courses']['id'], $course_madrasa_row['course_id']);
            }

            // echo json_encode($madrasa_courses_arr['courses']['id']);

            $result_all = $all_courses->getAllCourses();
            $row_count = $result_all->rowCount();
            if ($row_count > 0) {
                while ($row = $result_all->fetch(PDO::FETCH_ASSOC)) {
                          extract($row);
                          // echo $row['id'];
                          if (!in_array($row['id'], $madrasa_courses_arr['courses']['id']))
                          {
                            // echo "not found";
                            array_push($course_arr['data'], $row);
                          }

                }
               
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

