<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/subject.php';
include_once '../../models/teacher.php';
include_once '../../models/subjectTeachers.php';
// include_once '../../models/TeacherMadrasas.php';


$subject = new Subject($db);

$subject_teachers = new SubjectTeachers($db);

// $madrasa_teachers = new TeacherMadrasas($db);

$teacher = new Teacher($db);

$teacher_id = isset($_GET['teacher_id']) ?  $_GET['teacher_id'] : 0;

$madrasa_id = isset($_GET['madrasa_id']) ?  $_GET['madrasa_id'] : 0;


if (isset($teacher_id) and isset($madrasa_id)) {
    if(empty($teacher_id) or $teacher_id == '0' or empty($madrasa_id) or $madrasa_id == '0' ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid teacher id  and madrasa id')
        );
    } else {

                    //get subject list from subjectTeachers model n db
                    $subject_teachers->teacher_id = $teacher_id;

                    $subject_teachers->madrasa_id = $madrasa_id;

                    $result = $subject_teachers->getSingleMadrasaTeacherSubjects();

                    $row_count = $result->rowCount();

                    $subject_arr = array();
                    $subject_arr['data'] = array();

                    if($row_count > 0){
                            //subjects found for Madrasa Teacher
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                //echo "row_db process ping";
                                //extract($row);
                                // echo json_encode($row);
                                // $subject_id = $row['subject_id'];
                                $subject = new Subject($db);
                                $subject->id = $row['subject_id'];
                                 $result2 = $subject->getSingleSubjectById();
                                //  $row_count = $result->rowCount();
                                 $row_subject = $result2->fetch(PDO::FETCH_ASSOC);
                                    extract($row_subject);
                                 $subject = array(
                                       'id' => $id,
                                       'title' => $title,
                                       'description' => $description,
                                       'course_id' => $course_id,
                                       'serial_no' => $serial_no,
                                       'topic_count' => $topic_count,
                                       'status' => $status
                                   );

                                   array_push($subject_arr['data'], $subject);
                               
                            }       //while ends of subject_teachers get

                            echo json_encode($subject_arr);
                    }
                    else{
                        array_push($subject_arr['data'], 
                            array(
                                'id' => 'null',
                                'title' => 'no subjects for this teacher present'
                                )
                        );
                            echo json_encode($subject_arr);

                    }

    }


}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Teacher ID And Madrasa ID')
    );
}


?>

