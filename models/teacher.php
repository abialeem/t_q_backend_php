<?php
class Teacher
{
    private $conn;
    private $table = 'teachers';

    public $id;
    public $title;
    public $address;
    public $user_id;
    public $madrasa_id;
    public $courses_count;
    public $subjects_count;
    public $status;
    public $created_at;
    public $updated_at;
    public $deleted_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

// GET ALL Teachers
public function getAllTeachers()
{
    $query = 'SELECT *
                FROM ' . $this->table;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}
// GET SINGLE Teacher BY ID
public function getSingleTeacherById()
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


