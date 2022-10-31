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





}