<?php
class Student
{
    private $conn;
    private $table = 'students';

    public $id;
    public $title;
    public $address;
    public $user_id;
    public $madrasa_id;
    public $course_id;
    public $its_number;
    public $status;
    public $created_at;
    public $updated_at;
    public $deleted_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

// GET ALL Students
public function getAllStudents()
{
    $query = 'SELECT *
                FROM ' . $this->table;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}
// GET SINGLE Student BY ID
public function getSingleStudentById()
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


