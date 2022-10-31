<?php
class Madrasa
{
    private $conn;
    private $table = 'madrasas';

    public $id;
    public $madrasa_title;
    public $madrasa_address;
    public $madrasa_student_count;
    public $madrasa_teacher_count;
    public $madrasa_jamiat_id;
    public $madrasa_jamaat_id;
    public $status;
    public $created_at;
    public $updated_at;
    public $deleted_at;


    public function __construct($db)
    {
        $this->conn = $db;
    }
// GET ALL Madrasas
    public function getAllMadrasas()
    {
        $query = 'SELECT *
                    FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
// GET SINGLE Madrasa BY ID
    public function getSingleMadrasaById()
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


    // //GET Madrasa Title
    // public function getSellerName(){
    //     $query = 'SELECT name FROM sellers WHERE id = :id  ';
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bindParam(':id', $this->id);
    //     $stmt->execute();
    //     return $stmt;
    // }

// GET SINGLE Madrasa Principal BY Madrasa ID
    public function getMadrasaPrincipal(){
        $query = 'SELECT * FROM principals WHERE madrasa_id = :id  ';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }

// GET SINGLE Madrasa Teachers BY Madrasa ID
public function getMadrasaTeachers(){
    $query = 'SELECT * FROM teachers WHERE madrasa_id = :id  ';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $this->id);
    $stmt->execute();
    return $stmt;
}

// GET SINGLE Madrasa Students BY Madrasa ID
public function getMadrasaStudents(){
    $query = 'SELECT * FROM students WHERE madrasa_id = :id  ';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $this->id);
    $stmt->execute();
    return $stmt;
}



}