<?php
class TeacherMadrasas
{
    private $conn;
    private $table = 'teacher_madrasas';

    public $id;
    public $teacher_id;
    public $madrasa_id;
    public $assigned_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //GET Single Teacher's Madrasas

public function getSingleTeacherMadrasas()
{
    $query = 'SELECT madrasa_id
                FROM ' . $this->table .'
                WHERE teacher_id = :teacher_id
                ';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':teacher_id', $this->teacher_id);
    $stmt->execute();
    return $stmt;
}

    //GET Single Madrasa's Teachers

public function getSingleMadrasaTeachers()
{
        $query = 'SELECT teacher_id
                    FROM ' . $this->table .'
                    WHERE madrasa_id = :madrasa_id
                    ';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':madrasa_id', $this->madrasa_id);
        $stmt->execute();
        return $stmt;
}

    //Assign Teacher To Madrasa 
public function assignTeacherToMadrasa()
{
    $query = 'INSERT INTO ' . $this->table . '
                    SET
                        teacher_id = :teacher_id,
                        madrasa_id = :madrasa_id,
                        assigned_at = CURRENT_TIMESTAMP()
                        ';
        $stmt = $this->conn->prepare($query);
        $this->teacher_id = htmlspecialchars(strip_tags($this->teacher_id));
        $this->madrasa_id = htmlspecialchars(strip_tags($this->madrasa_id));
        $stmt->bindParam(':teacher_id', $this->teacher_id);
        $stmt->bindParam(':madrasa_id', $this->madrasa_id);
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

    //Unassign teacher madrasa entry
public function unassignTeacherFromMadrasa()
{
    $query = "DELETE FROM " . $this->table . " 
    WHERE 
        teacher_id = :teacher_id 
        AND madrasa_id = :madrasa_id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":teacher_id", $this->teacher_id);
    $stmt->bindParam(":madrasa_id", $this->madrasa_id);
    $stmt->execute();
    return $stmt;

}

}
