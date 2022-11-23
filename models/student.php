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

//GET All Unassigned Students

public function getUnassignedStudents()
{
    $query = 'SELECT *
                FROM ' . $this->table .'
                WHERE madrasa_id = 0
                ';
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

// GET All Students BY Madrasa ID
public function getStudentsByMadrasaId()
{
    $query = 'SELECT 
                *
                FROM ' . $this->table . ' 
                    WHERE 
                        madrasa_id = :madrasa_id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':madrasa_id', $this->madrasa_id);
    $stmt->execute();
    return $stmt;
}

//check if its_number is registered already
public function isRegistered()
    {
        $query = "SELECT id FROM " . $this->table . " 
                    WHERE 
                        its_number = :its_number 
                        AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($query);
        $this->its_number = htmlspecialchars(strip_tags($this->its_number));
        $stmt->bindParam(':its_number', $this->its_number);
        $stmt->execute();
        return $stmt;
    }


//add a new student
public function addStudent()
    {
        $query = 'INSERT INTO ' . $this->table . '
                    SET
                        title = :title,
                        address = :address,
                        user_id = :user_id,
                        madrasa_id = :madrasa_id,
                        course_id = :course_id,
                        its_number = :its_number,
                        status = :status,
                        updated_at = CURRENT_TIMESTAMP()
                        ';
        $stmt = $this->conn->prepare($query);
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->madrasa_id = htmlspecialchars(strip_tags($this->madrasa_id));
        $this->course_id = htmlspecialchars(strip_tags($this->course_id));
        $this->its_number = htmlspecialchars(strip_tags($this->its_number));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':madrasa_id', $this->madrasa_id);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':its_number', $this->its_number);
        $stmt->bindParam(':status', $this->status);
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


       // assign student to a madrasa
 public function assignStudentToMadrasa()
       {
       $query = "UPDATE " . $this->table . " 
       SET 
           madrasa_id = :madrasa_id 
           WHERE 
               id = :id 
               ";
   $stmt = $this->conn->prepare($query);
   $stmt->bindParam(":madrasa_id", $this->madrasa_id);
   $stmt->bindParam(":id", $this->id);
   try {
       if ($stmt->execute()) {
           return true;
       }
   } catch (Exception $e) {
       printf('Exception: %s.\n', $e);
       return false;
   }
   printf('Error: %s.\n', $stmt->error);
   return false;
   
       } 
   
   //check if a student is unassigned 
 public function isStudentUnassigned()
 {
     $query = 'SELECT *
                 FROM ' . $this->table .'
                 WHERE madrasa_id = 0
                 AND id = :id
                 ';
     $stmt = $this->conn->prepare($query);
     $stmt->bindParam(':id', $this->id);
     $stmt->execute();
     return $stmt;
 }
   
     //unassign student from a madrasa   
       public function unassignStudentFromMadrasa()
       {
       $query = "UPDATE " . $this->table . " 
       SET 
           madrasa_id = 0 
           WHERE 
               id = :id 
               ";
               $stmt = $this->conn->prepare($query);
               $stmt->bindParam(":id", $this->id);
               try {
                   if ($stmt->execute()) {
                       return true;
                   }
               } catch (Exception $e) {
                   printf('Exception: %s.\n', $e);
                   return false;
               }
               printf('Error: %s.\n', $stmt->error);
               return false;
               
       }


}



?>


