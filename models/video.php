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

//check if video of a specific topic of subject of specific course exists

public function videoExists(){
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

// add a video to db

public function addVideo()
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
                video_src = :video_src,
                updated_at = CURRENT_TIMESTAMP()';
    $stmt = $this->conn->prepare($query);
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->course_id = htmlspecialchars(strip_tags($this->course_id));
        $this->subject_id = htmlspecialchars(strip_tags($this->subject_id));
        $this->topic_id = htmlspecialchars(strip_tags($this->topic_id));
        $this->serial_no = htmlspecialchars(strip_tags($this->serial_no));
        $this->attachment_count = htmlspecialchars(strip_tags($this->attachment_count));
        $this->video_src = htmlspecialchars(strip_tags($this->video_src));
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':subject_id', $this->subject_id);
        $stmt->bindParam(':topic_id', $this->topic_id);
        $stmt->bindParam(':serial_no', $this->serial_no);
        $stmt->bindParam(':attachment_count', $this->attachment_count);
        $stmt->bindParam(':video_src', $this->video_src);
       
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


//get serial no's of videos of topic of a subject of a course
public function getAllVideosSerialsByCourseIDSubjectIDTopicID(){

    $query = "SELECT serial_no FROM " . $this->table . " 
    WHERE 
         course_id = :course_id
         AND subject_id = :subject_id
         AND topic_id = :topic_id
        AND deleted_at IS NULL";
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


}



?>


