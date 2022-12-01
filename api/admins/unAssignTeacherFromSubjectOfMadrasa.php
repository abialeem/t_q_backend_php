<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once('../../core/initialize.php');
include_once '../../models/subjectTeachers.php';

$subject_teachers = new SubjectTeachers($db);

$teacher_id = isset($_GET['teacher_id']) ?  $_GET['teacher_id'] : 0;

$madrasa_id = isset($_GET['madrasa_id']) ?  $_GET['madrasa_id'] : 0;

$subject_id = isset($_GET['subject_id']) ?  $_GET['subject_id'] : 0;

if (isset($teacher_id) and isset($madrasa_id) and isset($subject_id)) {
    if(empty($teacher_id) or $teacher_id == '0' or empty($madrasa_id) or $madrasa_id == '0'  or empty($subject_id) or $subject_id == '0' ) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please provide a valid subject id and teacher id  and madrasa id')
        );
    } else {

$subject_teachers->teacher_id = $teacher_id;

$subject_teachers->subject_id = $subject_id;

$subject_teachers->madrasa_id = $madrasa_id;

 //check if entry of this teacher with this madrasa for subject exists
 $result_check = $subject_teachers->teacherAssignedToSubjectForMadrasa();

 $check_row_count = $result_check->rowCount();
                if($check_row_count < 0)
                 {
                    http_response_code(201);
                    echo json_encode(
                        array('message' => 'Teacher is not assigned to this subject for given madrasa.')
                    );
        
                 }
                else{

                        // now unassign the teacher from subject
                        if($subject_teachers->unassignTeacherFromSubject())
                        {
                            http_response_code(201);
                            echo json_encode(
                                array('message' => 'Teacher unassigned from this subject for given madrasa.')
                            );

                        }
                        else{
                            http_response_code(422);
                            echo json_encode(
                                array('message' => 'operation failed...Teacher not unassigned from this subject for given madrasa.')
                            );
                        }
                }
    }

}
else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please Provide A Subject ID And Madrasa ID and Teacher ID')
    );
}

?>