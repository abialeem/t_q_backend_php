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


}



?>


