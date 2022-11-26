<?php
class Madrasa
{
    private $conn;
    private $table = 'madrasas';

    public $id;
    public $madrasa_title;
    public $madrasa_address;
    public $madrasa_student_count ;
    public $madrasa_teacher_count ;
    public $madrasa_jamiat_id;
    public $madrasa_jamaat_id;
    public $status ;
    public $created_at ;
    public $updated_at ;
    public $deleted_at ;


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

     //get single madrasa_title by id
    public function getSingleMadrasaTitleById(){
        $query = 'SELECT 
                    madrasa_title
                    FROM ' . $this->table . ' 
                        WHERE 
                            id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }

//check if madrasa in db already
    public function madrasaExists()
    {
        $query = "SELECT id FROM " . $this->table . " 
                    WHERE 
                        madrasa_title = :madrasa_title
                        AND madrasa_address = :madrasa_address
                        AND madrasa_jamiat_id = :madrasa_jamiat_id
                        AND madrasa_jamaat_id = :madrasa_jamaat_id
                        AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($query);
        $this->madrasa_title = htmlspecialchars(strip_tags($this->madrasa_title));
        $this->madrasa_address = htmlspecialchars(strip_tags($this->madrasa_address));
        $this->madrasa_jamiat_id = htmlspecialchars(strip_tags($this->madrasa_jamiat_id));
        $this->madrasa_jamaat_id = htmlspecialchars(strip_tags($this->madrasa_jamaat_id));
        $stmt->bindParam(':madrasa_title', $this->madrasa_title);
        $stmt->bindParam(':madrasa_address', $this->madrasa_address);
        $stmt->bindParam(':madrasa_jamiat_id', $this->madrasa_jamiat_id);
        $stmt->bindParam(':madrasa_jamaat_id', $this->madrasa_jamaat_id);
        $stmt->execute();
        return $stmt;
    }



// Add madrasa to db 

public function addMadrasa()
{
    $query = 'INSERT INTO ' . $this->table . '
                SET
                madrasa_title = :madrasa_title,
                madrasa_address = :madrasa_address,
                madrasa_jamiat_id = :madrasa_jamiat_id,
                madrasa_jamaat_id = :madrasa_jamaat_id,
                madrasa_student_count   = 0,
                madrasa_teacher_count = 0,
                status = 1,
                updated_at = CURRENT_TIMESTAMP()';
    $stmt = $this->conn->prepare($query);
        $this->madrasa_title = htmlspecialchars(strip_tags($this->madrasa_title));
        $this->madrasa_address = htmlspecialchars(strip_tags($this->madrasa_address));
        $this->madrasa_jamiat_id = htmlspecialchars(strip_tags($this->madrasa_jamiat_id));
        $this->madrasa_jamaat_id = htmlspecialchars(strip_tags($this->madrasa_jamaat_id));
        $stmt->bindParam(':madrasa_title', $this->madrasa_title);
        $stmt->bindParam(':madrasa_address', $this->madrasa_address);
        $stmt->bindParam(':madrasa_jamiat_id', $this->madrasa_jamiat_id);
        $stmt->bindParam(':madrasa_jamaat_id', $this->madrasa_jamaat_id);
       
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

    //increment teacher count
public function incrementTeacherCount(){
    $query = "UPDATE " . $this->table . " 
    SET 
    madrasa_teacher_count = madrasa_teacher_count + 1, 
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

    //increment teacher count
public function decrementTeacherCount(){
        $query = "UPDATE " . $this->table . " 
        SET 
        madrasa_teacher_count = madrasa_teacher_count - 1, 
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

    //increment student count
public function incrementStudentCount(){
        $query = "UPDATE " . $this->table . " 
        SET 
        madrasa_student_count = madrasa_student_count + 1, 
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

   //decrement student count
public function decrementStudentCount(){
    $query = "UPDATE " . $this->table . " 
    SET 
    madrasa_student_count = madrasa_student_count - 1, 
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