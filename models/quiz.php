<?php
class Quiz
{
    private $conn;
    private $table = 'quizzes';

    public $id;
    public $title;
    public $description;
    public $serial_no;
    public $question_count;
    public $attachment_count;
    public $topic_id;
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

// GET ALL Quizzes
public function getAllQuizzes()
{
    $query = 'SELECT *
                FROM ' . $this->table;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}
// GET SINGLE Quizzes BY ID
public function getSingleQuizById()
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

//Get All quizzes of a single course_id
public function getSingleCourseQuizzes()
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

//Get All quizzes of a single subject_id
public function getSingleSubjectQuizzes()
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

//Get All quizzes of a single topic_id
public function getSingleTopicQuizzes()
{
    $query = 'SELECT 
    *
    FROM ' . $this->table . ' 
        WHERE 
            topic_id = :topic_id ORDER BY serial_no';
$stmt = $this->conn->prepare($query);
$stmt->bindParam(':topic_id', $this->topic_id);
$stmt->execute();
return $stmt;

}

// GET SINGLE Quiz Title BY ID
public function getSingleQuizTitleById()
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

//check if quiz of a specific topic of subject of specific course exists

public function quizExists(){
    $query = "SELECT id FROM " . $this->table . " 
                    WHERE 
                        title = :title
                        AND course_id = :course_id
                        AND subject_id = :subject_id
                        AND topic_id = :topic_id
                        AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($query);
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->course_id = htmlspecialchars(strip_tags($this->course_id));
        $this->subject_id = htmlspecialchars(strip_tags($this->subject_id));
        $this->topic_id = htmlspecialchars(strip_tags($this->topic_id));
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':subject_id', $this->subject_id);
        $stmt->bindParam(':topic_id', $this->topic_id);
        $stmt->execute();
        return $stmt;
}

// add a quiz to db

public function addQuiz()
{
    $query = 'INSERT INTO ' . $this->table . '
                SET
                title = :title,
                description = :description,
                course_id = :course_id,
                subject_id = :subject_id,
                topic_id = :topic_id,
                serial_no = :serial_no,
                attachment_count  = :attachment_count,
                question_count = :question_count,
                updated_at = CURRENT_TIMESTAMP()';
    $stmt = $this->conn->prepare($query);
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->course_id = htmlspecialchars(strip_tags($this->course_id));
        $this->subject_id = htmlspecialchars(strip_tags($this->subject_id));
        $this->topic_id = htmlspecialchars(strip_tags($this->topic_id));
        $this->serial_no = htmlspecialchars(strip_tags($this->serial_no));
        $this->attachment_count = htmlspecialchars(strip_tags($this->attachment_count));
        $this->question_count = htmlspecialchars(strip_tags($this->question_count));
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':subject_id', $this->subject_id);
        $stmt->bindParam(':topic_id', $this->topic_id);
        $stmt->bindParam(':serial_no', $this->serial_no);
        $stmt->bindParam(':attachment_count', $this->attachment_count);
        $stmt->bindParam(':question_count', $this->question_count);
       
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

//get serial no's of quizzes of topic of a subject of a course
public function getAllQuizzesSerialsByCourseIDSubjectIDTopicID(){

    $query = "SELECT serial_no FROM " . $this->table . " 
    WHERE 
         course_id = :course_id
         AND subject_id = :subject_id
         AND topic_id = :topic_id
        AND deleted_at IS NULL ORDER BY serial_no";
$stmt = $this->conn->prepare($query);
$this->course_id = htmlspecialchars(strip_tags($this->course_id));
$this->subject_id = htmlspecialchars(strip_tags($this->subject_id));
$this->topic_id = htmlspecialchars(strip_tags($this->topic_id));
$stmt->bindParam(':course_id', $this->course_id);
$stmt->bindParam(':subject_id', $this->subject_id);
$stmt->bindParam(':topic_id', $this->topic_id);
$stmt->execute();
return $stmt;

}

//get serial no's of quizzes of topic by topic id
public function getAllQuizzesSerialsByTopicID(){

    $query = "SELECT serial_no FROM " . $this->table . " 
    WHERE 
         topic_id = :topic_id
        AND deleted_at IS NULL ORDER BY serial_no";
$stmt = $this->conn->prepare($query);
$this->topic_id = htmlspecialchars(strip_tags($this->topic_id));
$stmt->bindParam(':topic_id', $this->topic_id);
$stmt->execute();
return $stmt;

}



public function incrementAttachmentCount(){
    $query = "UPDATE " . $this->table . " 
    SET 
    attachment_count = attachment_count + 1, 
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


public function decrementAttachmentCount(){
    $query = "UPDATE " . $this->table . " 
    SET 
    attachment_count = attachment_count - 1, 
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



public function incrementQuestionCount(){
    $query = "UPDATE " . $this->table . " 
    SET 
    question_count = question_count + 1, 
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


public function decrementQuestionCount(){
    $query = "UPDATE " . $this->table . " 
    SET 
    question_count = question_count - 1, 
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


