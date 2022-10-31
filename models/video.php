<?php
class Video
{
    private $conn;
    private $table = 'videos';

    public $id;
    public $title;
    public $description;
    public $serial_no;
    public $video_src;
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

// GET ALL Videos
public function getAllVideos()
{
    $query = 'SELECT *
                FROM ' . $this->table;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}
// GET SINGLE Video BY ID
public function getSingleVideoById()
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


