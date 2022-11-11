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

//get teacher title
    public function getTeacherTitle()
    {

        $query ='SELECT title 
        FROM 
        ' . $this->table . ' 
        WHERE 
            id = :id';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
            return $stmt;

    }


//get teacher address
public function getTeacherAddress()
{

    $query ='SELECT address 
    FROM 
    ' . $this->table . ' 
    WHERE 
        id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;

}


//update teacher title
public function updateTeacherTitle()
    {
        $query = "UPDATE " . $this->table . " 
                    SET 
                        title = :title,
                        updated_at = CURRENT_TIMESTAMP() 
                            WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->title = htmlspecialchars(strip_tags($this->title));
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':title', $this->title);
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

    
//update teacher address
public function updateTeacherAddress()
{
    $query = "UPDATE " . $this->table . " 
                SET 
                    address = :address,
                    updated_at = CURRENT_TIMESTAMP() 
                        WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $this->address = htmlspecialchars(strip_tags($this->address));
    $stmt->bindParam(':id', $this->id);
    $stmt->bindParam(':address', $this->address);
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

//add a new teacher
public function addTeacher()
    {
        $query = 'INSERT INTO ' . $this->table . '
                    SET
                        title = :title,
                        address = :address,
                        user_id = :user_id,
                        madrasa_id = :madrasa_id,
                        courses_count = :courses_count,
                        subjects_count = :subjects_count,
                        status = :status,
                        updated_at = CURRENT_TIMESTAMP()
                        ';
        $stmt = $this->conn->prepare($query);
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->madrasa_id = htmlspecialchars(strip_tags($this->madrasa_id));
        $this->courses_count = htmlspecialchars(strip_tags($this->courses_count));
        $this->subjects_count = htmlspecialchars(strip_tags($this->subjects_count));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':madrasa_id', $this->madrasa_id);
        $stmt->bindParam(':courses_count', $this->courses_count);
        $stmt->bindParam(':subjects_count', $this->subjects_count);
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

//update a teacher 

public function updateTeacher()
    {
        $query = "UPDATE " . $this->table . " 
                    SET 
                        title = :title, 
                        address = :address,
                        user_id = :user_id,
                        madrasa_id = :madrasa_id,
                        courses_count = :courses_count,
                        subjects_count = :subjects_count,
                        status = :status
                        updated_at = CURRENT_TIMESTAMP() 
                            WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->madrasa_id = htmlspecialchars(strip_tags($this->madrasa_id));
        $this->courses_count = htmlspecialchars(strip_tags($this->courses_count));
        $this->subjects_count = htmlspecialchars(strip_tags($this->subjects_count));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':madrasa_id', $this->madrasa_id);
        $stmt->bindParam(':courses_count', $this->courses_count);
        $stmt->bindParam(':subjects_count', $this->subjects_count);
        $stmt->bindParam(':status', $this->status);
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

//update courses_count and subjects_count of teacher by id
public function updateTeacherCoursesSubjectsCount()
    {
        $query = "UPDATE " . $this->table . " 
                    SET 
                        courses_count = :courses_count,
                        subjects_count = :subjects_count,
                        updated_at = CURRENT_TIMESTAMP() 
                            WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->courses_count = htmlspecialchars(strip_tags($this->courses_count));
        $this->subjects_count = htmlspecialchars(strip_tags($this->subjects_count));
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':courses_count', $this->courses_count);
        $stmt->bindParam(':subjects_count', $this->subjects_count);
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

//delete a teacher
    public function deleteTeacher()
    {
        $query = "UPDATE " . $this->table . " 
                    SET 
                        deleted_at = CURRENT_TIMESTAMP() 
                            WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch(Exception $e) {
            printf('Exception: %s.\n', $e);    
        }
        printf('Error: %s.\n', $stmt->error);
        return false;
    }



}