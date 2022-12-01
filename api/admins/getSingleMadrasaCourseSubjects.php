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

$course_id = isset($_GET['course_id']) ?  $_GET['course_id'] : 0;

$madrasa_id = isset($_GET['madrasa_id']) ?  $_GET['madrasa_id'] : 0;


if (isset($course_id) and isset($madrasa_id)) {
    if(empty($course_id) or $course_id == '0' or empty($madrasa_id) or $madrasa_id == '0' ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid course id  and madrasa id')
        );
    } else {

        $subject->course_id = $course_id;

        $subject_teachers->course_id = $course_id;

        $subject_teachers->madrasa_id = $madrasa_id;

        // $madrasa_teachers->madrasa_id = $madrasa_id;

        $result = $subject->getSingleCourseSubjects();
        $row_count = $result->rowCount();
        
        $subject_arr = array();
        $subject_arr['data'] = array();

        if ($row_count > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                        //now check this subject's assigned teacher in subject_teacher class , db = teacher_subjects
                    $subject_teachers->subject_id = $row['id'];

                    $subject_teacher_result = $subject_teachers->getSingleMadrasaSubjectTeacher();
                    $row2_count = $subject_teacher_result->rowCount();
                    if($row2_count>0){
                        $subject_teacher_row = $subject_teacher_result->fetch(PDO::FETCH_ASSOC);

                            // //check if teacher exists for madrasa still
                            // $madrasa_teachers->teacher_id = $subject_teacher_row['teacher_id'];

                            // $result_madrasa_teacher_check = $madrasa_teachers->teacherAssignedToMadrasa();
                            // // $madrasa_teacher_row = $result_madrasa_teacher_check->fetch(PDO::FETCH_ASSOC);
                            // if($result_madrasa_teacher_check->rowCount() > 0){
                                    //teacher exists ..so keep the teacher
                                    $teacher_id = $subject_teacher_row['teacher_id'];

                                    $teacher->id = $teacher_id;
                                    $result_teacher = $teacher->getTeacherTitle();
                                    $teacher_row = $result_teacher->fetch(PDO::FETCH_ASSOC);
                                    $teacher_title = $teacher_row['title'];

                            // }
                            // else{
                            //         //teacher no longer assigned to madrasa ..that was assigned to subject
                            //         $teacher_id = 0;
                            //         $teacher_title = '';
                            // }

                       
                    }
                    else{
                        $teacher_id = 0;
                        $teacher_title = '';
                    }
                $subject = array(
                    'id' => $id,
                    'title' => $title,
                    'description' => $description,
                    'course_id' => $course_id,
                    'teacher_id' => $teacher_id,
                    'teacher_title' => $teacher_title,
                    'serial_no' => $serial_no,
                    'topic_count' => $topic_count,
                    'status' => $status
                    );
                array_push($subject_arr['data'], $subject);
            }

            echo json_encode($subject_arr);

        } else {
            array_push($subject_arr['data'], 
            array(
                'id' => 'null',
                'title' => 'no subjects for this course present'
                )
        );
            echo json_encode($subject_arr);
        }


    }


}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Course ID And Madrasa ID')
    );
}


?>

