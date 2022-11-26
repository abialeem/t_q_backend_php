<?php
class Subject
{
    private $conn;
    private $table = 'subjects';

    public $id;
    public $title;
    public $description;
    public $course_id;
    public $serial_no;
    public $topic_count;
    public $status;
    public $created_at;
    public $updated_at;
    public $deleted_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

// GET ALL Subjects
public function getAllSubjects()
{
    $query = 'SELECT *
                FROM ' . $this->table;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}
// GET SINGLE Subject BY ID
public function getSingleSubjectById()
{
    $query = 'SELECT 
                *
                FROM ' . $this->table . ' 
                    WHERE 
                        id = :id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $this->id);
    $stmt->execute();
    return $stmt;
}

//Get All subjects of a single course_id
public function getSingleCourseSubjects()
{
    $query = 'SELECT 
    *
    FROM ' . $this->table . ' 
        WHERE 
            course_id = :course_id ORDER BY serial_no ';
$stmt = $this->conn->prepare($query);
$stmt->bindParam(':course_id', $this->course_id);
$stmt->execute();
return $stmt;

}

// GET SINGLE Subject Title BY ID
public function getSingleSubjectTitleById()
{
    $query = 'SELECT 
                title
                FROM ' . $this->table . ' 
                    WHERE 
                        id = :id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $this->id);
    $stmt->execute();
    return $stmt;
}

//get serials of a course's subjects by course id

public function getAllSubjectsSerialsByCourseID(){

    $query = "SELECT serial_no FROM " . $this->table . " 
    WHERE 
         course_id = :course_id
        AND deleted_at IS NULL ORDER BY serial_no";
$stmt = $this->conn->prepare($query);
$this->course_id = htmlspecialchars(strip_tags($this->course_id));
$stmt->bindParam(':course_id', $this->course_id);
$stmt->execute();
return $stmt;

}


//check if subject title for course id exists

public function subjectExists(){
    $query = "SELECT id FROM " . $this->table . " 
                    WHERE 
                        title = :title
                        AND course_id = :course_id
                        AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($query);
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->course_id = htmlspecialchars(strip_tags($this->course_id));
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->execute();
        return $stmt;
}

//add subject to db

public function addSubject()
{
    $query = 'INSERT INTO ' . $this->table . '
                SET
                title = :title,
                description = :description,
                course_id = :course_id,
                serial_no = :serial_no,
                topic_count  = :topic_count,
                updated_at = CURRENT_TIMESTAMP()';
    $stmt = $this->conn->prepare($query);
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->course_id = htmlspecialchars(strip_tags($this->course_id));
        $this->serial_no = htmlspecialchars(strip_tags($this->serial_no));
        $this->topic_count = htmlspecialchars(strip_tags($this->topic_count));
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':serial_no', $this->serial_no);
        $stmt->bindParam(':topic_count', $this->topic_count);
       
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

public function incrementTopicCount(){
    $query = "UPDATE " . $this->table . " 
    SET 
        topic_count = topic_count + 1, 
        updated_at = CURRENT_TIMESTAMP() 
            WHERE id = :id";
$stmt = $this->conn->prepare($query);
$stmt->bindParam(":id", $this->id);
try {
if ($stmt->execute()) {
return true;
}
} catch(Exception $e) {
printf('Exception: %s.\n', $e);
return false;
}
printf('Error: %s.\n', $stmt->error);
return false;
}

public function decrementTopicCount(){
    $query = "UPDATE " . $this->table . " 
    SET 
        topic_count = topic_count - 1, 
        updated_at = CURRENT_TIMESTAMP() 
            WHERE id = :id";
$stmt = $this->conn->prepare($query);
$stmt->bindParam(":id", $this->id);
try {
if ($stmt->execute()) {
return true;
}
} catch(Exception $e) {
printf('Exception: %s.\n', $e);
return false;
}
printf('Error: %s.\n', $stmt->error);
return false;
}

}   //class ends here



?>


