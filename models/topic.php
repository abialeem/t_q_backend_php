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
                        id = :id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $this->id);
    $stmt->execute();
    return $stmt;
}


}



?>


