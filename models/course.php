<?php
class Course
{
    private $conn;
    private $table = 'courses';

    public $id;
    public $title;
    public $description;
    public $course_type;
    public $skill_level;
    public $language;
    public $subjects_count;
    public $status;
    public $created_at;
    public $updated_at;
    public $deleted_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }
 
// GET ALL Courses
public function getAllCourses()
{
    $query = 'SELECT *
                FROM ' . $this->table;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}
// GET SINGLE Course BY ID
public function getSingleCourseById()
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

// GET SINGLE Course Title BY ID
public function getSingleCourseTitleById()
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


//check if course title exists

public function titleExists(){
    $query = "SELECT id FROM " . $this->table . " 
                    WHERE 
                        title = :title 
                        AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($query);
        $this->title = htmlspecialchars(strip_tags($this->title));
        $stmt->bindParam(':title', $this->title);
        $stmt->execute();
        return $stmt;
}


// Add course to db 

public function addCourse()
{
    $query = 'INSERT INTO ' . $this->table . '
                SET
                title = :title,
                description = :description,
                course_type = :course_type,
                skill_level = :skill_level,
                language  = :language,
                status = 1,
                updated_at = CURRENT_TIMESTAMP()';
    $stmt = $this->conn->prepare($query);
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->course_type = htmlspecialchars(strip_tags($this->course_type));
        $this->skill_level = htmlspecialchars(strip_tags($this->skill_level));
        $this->language = htmlspecialchars(strip_tags($this->language));
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':course_type', $this->course_type);
        $stmt->bindParam(':skill_level', $this->skill_level);
        $stmt->bindParam(':language', $this->language);
       
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

public function incrementSubjectCount(){
    $query = "UPDATE " . $this->table . " 
    SET 
        subjects_count = subjects_count + 1, 
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

public function decrementSubjectCount(){
    $query = "UPDATE " . $this->table . " 
    SET 
    subjects_count = subjects_count - 1, 
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




}



?>


