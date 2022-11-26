<?php
class Topic
{
    private $conn;
    private $table = 'topics';

    public $id;
    public $title;
    public $description;
    public $serial_no;
    public $video_count;
    public $quiz_count;
    public $subject_id;
    public $course_id;
    public $status;
    public $created_at;
    public $updated_at;
    public $deleted_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

// GET ALL Topics
public function getAllTopics()
{
    $query = 'SELECT *
                FROM ' . $this->table;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}
// GET SINGLE Topic BY ID
public function getSingleTopicById()
{
    $query = 'SELECT 
                *
                FROM ' . $this->table . ' 
                    WHERE 
                        id = :id ';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $this->id);
    $stmt->execute();
    return $stmt;
}

//Get All topics of a single course_id
public function getSingleCourseTopics()
{
    $query = 'SELECT 
    *
    FROM ' . $this->table . ' 
        WHERE 
            course_id = :course_id ORDER BY serial_no';
$stmt = $this->conn->prepare($query);
$stmt->bindParam(':course_id', $this->course_id);
$stmt->execute();
return $stmt;

}

//Get All topics of a single subject_id
public function getSingleSubjectTopics()
{
    $query = 'SELECT 
    *
    FROM ' . $this->table . ' 
        WHERE 
            subject_id = :subject_id ORDER BY serial_no';
$stmt = $this->conn->prepare($query);
$stmt->bindParam(':subject_id', $this->subject_id);
$stmt->execute();
return $stmt;

}

// GET SINGLE Topic Title BY ID
public function getSingleTopicTitleById()
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

//check if topic of a specific subject of specific course exists

public function topicExists(){
    $query = "SELECT id FROM " . $this->table . " 
                    WHERE 
                        title = :title
                        AND course_id = :course_id
                        AND subject_id = :subject_id
                        AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($query);
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->course_id = htmlspecialchars(strip_tags($this->course_id));
        $this->subject_id = htmlspecialchars(strip_tags($this->subject_id));
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':subject_id', $this->subject_id);
        $stmt->execute();
        return $stmt;
}


// add a topic to db

public function addTopic()
{
    $query = 'INSERT INTO ' . $this->table . '
                SET
                title = :title,
                description = :description,
                course_id = :course_id,
                subject_id = :subject_id,
                serial_no = :serial_no,
                quiz_count  = :quiz_count,
                video_count = :video_count,
                updated_at = CURRENT_TIMESTAMP()';
    $stmt = $this->conn->prepare($query);
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->course_id = htmlspecialchars(strip_tags($this->course_id));
        $this->subject_id = htmlspecialchars(strip_tags($this->subject_id));
        $this->serial_no = htmlspecialchars(strip_tags($this->serial_no));
        $this->quiz_count = htmlspecialchars(strip_tags($this->quiz_count));
        $this->video_count = htmlspecialchars(strip_tags($this->video_count));
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':subject_id', $this->subject_id);
        $stmt->bindParam(':serial_no', $this->serial_no);
        $stmt->bindParam(':quiz_count', $this->quiz_count);
        $stmt->bindParam(':video_count', $this->video_count);
       
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



//get serial no's of topics of a subject of a course
public function getAllTopicsSerialsByCourseIDSubjectID(){

    $query = "SELECT serial_no FROM " . $this->table . " 
    WHERE 
         course_id = :course_id
         AND subject_id = :subject_id
        AND deleted_at IS NULL ORDER BY serial_no";
$stmt = $this->conn->prepare($query);
$this->course_id = htmlspecialchars(strip_tags($this->course_id));
$this->subject_id = htmlspecialchars(strip_tags($this->subject_id));
$stmt->bindParam(':course_id', $this->course_id);
$stmt->bindParam(':subject_id', $this->subject_id);
$stmt->execute();
return $stmt;

}
//get serial no's of topics of a subject by subject id
public function getAllTopicsSerialsBySubjectID(){
    $query = "SELECT serial_no FROM " . $this->table . " 
    WHERE 
          subject_id = :subject_id
        AND deleted_at IS NULL ORDER BY serial_no";
$stmt = $this->conn->prepare($query);
$this->subject_id = htmlspecialchars(strip_tags($this->subject_id));
$stmt->bindParam(':subject_id', $this->subject_id);
$stmt->execute();
return $stmt;
}


public function incrementVideoCount(){
    $query = "UPDATE " . $this->table . " 
    SET 
        video_count = video_count + 1, 
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

public function incrementQuizCount(){
    $query = "UPDATE " . $this->table . " 
    SET 
        quiz_count = quiz_count + 1, 
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

public function decrementVideoCount(){
    $query = "UPDATE " . $this->table . " 
    SET 
        video_count = video_count - 1, 
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

public function decrementQuizCount(){
    $query = "UPDATE " . $this->table . " 
    SET 
        quiz_count = quiz_count - 1, 
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


