<?php
class Principal
{
    private $conn;
    private $table = 'principals';

    public $id;
    public $title;
    public $address;
    public $user_id;
    public $status;
    public $madrasa_id;
    public $created_at;
    public $updated_at;
    public $deleted_at;



    public function __construct($db)
    {
        $this->conn = $db;
    }
// GET ALL Principals
    public function getAllPrincipals()
    {
        $query = 'SELECT *
                    FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    //check if a principal is unassigned 
    public function isPrincipalUnassigned()
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


//GET All Unassigned Principals

    public function getUnassignedPrincipals()
    {
        $query = 'SELECT *
                    FROM ' . $this->table .'
                    WHERE madrasa_id = 0
                    ';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

// GET SINGLE Principal BY ID
    public function getSinglePrincipalById()
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

// GET SINGLE Principal BY Madrasa ID
public function getSinglePrincipalByMadrasaId()
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

// GET SINGLE Principal BY Madrasa ID
public function getSinglePrincipalTitleByMadrasaId()
{
    $query = 'SELECT 
                title
                FROM ' . $this->table . ' 
                    WHERE 
                    madrasa_id = :madrasa_id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':madrasa_id', $this->madrasa_id);
    $stmt->execute();
    return $stmt;
}


//add principal to db

public function addPrincipal()
    {
        $query = 'INSERT INTO ' . $this->table . '
                    SET
                        title = :title,
                        address= :address,
                        user_id = :user_id,
                        madrasa_id = 0,
                        status=  1,
                        updated_at = CURRENT_TIMESTAMP()';
        $stmt = $this->conn->prepare($query);
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':user_id', $this->user_id);
        try {
            if ($stmt->execute()) {
                //return $stmt;
                return true;
            }
        } catch(Exception $e) {
            printf('Error: %s.\n', $e);
            return false;
        }
        printf('Error: %s.\n', $stmt->error);
        return false;
    }

public function assignPrincipalToMadrasa()
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

public function unassignPrincipalFromMadrasa()
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