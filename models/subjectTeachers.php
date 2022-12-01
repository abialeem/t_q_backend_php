<?php
class SubjectTeachers
{
    private $conn;
    private $table = 'teacher_subjects';

    public $id;
    public $teacher_id;
    public $subject_id;
    public $madrasa_id;
    public $course_id;
    public $created_at;
    public $updated_at;
    public $deleted_at;
    public $status;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //GET Single Teacher's Subjects 

public function getSingleTeacherSubjects()
{
    $query = 'SELECT *
                FROM ' . $this->table .'
                WHERE teacher_id = :teacher_id
                ';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':teacher_id', $this->teacher_id);
    $stmt->execute();
    return $stmt;
}

    //GET Single  Subject's Teacher Of A Madrasa 

    public function getSingleMadrasaSubjectTeacher()
    {
        $query = 'SELECT teacher_id
                    FROM ' . $this->table .'
                    WHERE subject_id = :subject_id
                    AND madrasa_id = :madrasa_id
                    AND course_id = :course_id
                    ';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':subject_id', $this->subject_id);
        $stmt->bindParam(':madrasa_id', $this->madrasa_id);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->execute();
        return $stmt;
    }

    //GET Single Teacher's Subjects For A Particular Course

public function getSingleCourseTeacherSubjects()
{
        $query = 'SELECT *
                    FROM ' . $this->table .'
                    WHERE teacher_id = :teacher_id
                    AND course_id = :course_id
                    ';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':teacher_id', $this->teacher_id);
        $stmt->execute();
        return $stmt;
}
    //GET Single Teacher's Subjects For A Particular Madrasa

    public function getSingleMadrasaTeacherSubjects()
    {
            $query = 'SELECT *
                        FROM ' . $this->table .'
                        WHERE teacher_id = :teacher_id
                        AND madrasa_id = :madrasa_id
                        ';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':madrasa_id', $this->madrasa_id);
            $stmt->bindParam(':teacher_id', $this->teacher_id);
            $stmt->execute();
            return $stmt;
    }

//check if  teacher subject pair exists
public function teacherAssignedToSubjectForMadrasa()
{
    $query = 'SELECT *
    FROM ' . $this->table .'
    WHERE teacher_id = :teacher_id
    AND subject_id = :subject_id
    AND madrasa_id = :madrasa_id
    ';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':teacher_id', $this->teacher_id);
    $stmt->bindParam(':subject_id', $this->subject_id);
    $stmt->bindParam(':madrasa_id', $this->madrasa_id);
    $stmt->execute();
    return $stmt;

}


    //Assign Teacher To Subject
public function assignTeacherToSubject()
{
    $query = 'INSERT INTO ' . $this->table . '
                    SET
                        course_id = :course_id,
                        madrasa_id = :madrasa_id,
                        subject_id = :subject_id,
                        teacher_id = :teacher_id,
                        status = 1,
                        updated_at = CURRENT_TIMESTAMP(),
                        created_at = CURRENT_TIMESTAMP()
                        ';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':madrasa_id', $this->madrasa_id);
        $stmt->bindParam(':teacher_id', $this->teacher_id);
        $stmt->bindParam(':subject_id', $this->subject_id);
        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch(Exception $e) {
            printf('Error: %s.\n', $e);
            return false;
        }
        printf('Error: %s.\n', $stmt->error);
        return false;
}

    //Unassign Teacher To Subject entry
public function unassignTeacherFromSubject()
{
    $query = "DELETE FROM " . $this->table . " 
    WHERE 
        subject_id = :subject_id 
        AND teacher_id = :teacher_id
        AND madrasa_id = :madrasa_id
        ";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':madrasa_id', $this->madrasa_id);
    $stmt->bindParam(':teacher_id', $this->teacher_id);
    $stmt->bindParam(':subject_id', $this->subject_id);
    $stmt->execute();
    return $stmt;

}

}
