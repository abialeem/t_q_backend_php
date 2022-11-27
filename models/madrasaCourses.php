<?php
class MadrasaCourses
{
    private $conn;
    private $table = 'madrasa_courses';

    public $id;
    public $madrasa_id;
    public $course_id;
    public $created_at;
    public $updated_at;
    public $deleted_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //GET Single Madrasa's Courses

public function getSingleMadrasaCourses()
{
    $query = 'SELECT course_id
                FROM ' . $this->table .'
                WHERE madrasa_id = :madrasa_id
                ';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':madrasa_id', $this->madrasa_id);
    $stmt->execute();
    return $stmt;
}

    //GET Single Course's Madrasas

public function getSingleCourseMadrasas()
{
        $query = 'SELECT madrasa_id
                    FROM ' . $this->table .'
                    WHERE course_id = :course_id
                    ';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->execute();
        return $stmt;
}

//check if  madrasa course pair exists
public function courseAssignedToMadrasa()
{
    $query = 'SELECT *
    FROM ' . $this->table .'
    WHERE madrasa_id = :madrasa_id
    AND course_id = :course_id
    ';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':madrasa_id', $this->madrasa_id);
    $stmt->bindParam(':course_id', $this->course_id);
    $stmt->execute();
    return $stmt;

}


    //Assign Course To Madrasa 
public function assignCourseToMadrasa()
{
    $query = 'INSERT INTO ' . $this->table . '
                    SET
                        course_id = :course_id,
                        madrasa_id = :madrasa_id,
                        updated_at = CURRENT_TIMESTAMP(),
                        created_at = CURRENT_TIMESTAMP()
                        ';
        $stmt = $this->conn->prepare($query);
        // $this->course_id = htmlspecialchars(strip_tags($this->course_id));
        // $this->madrasa_id = htmlspecialchars(strip_tags($this->madrasa_id));
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':madrasa_id', $this->madrasa_id);
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

    //Unassign course madrasa entry
public function unassignCourseFromMadrasa()
{
    $query = "DELETE FROM " . $this->table . " 
    WHERE 
        course_id = :course_id 
        AND madrasa_id = :madrasa_id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":course_id", $this->course_id);
    $stmt->bindParam(":madrasa_id", $this->madrasa_id);
    $stmt->execute();
    return $stmt;

}

}
