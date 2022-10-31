<?php
class Admin
{
    private $conn;
    private $table = 'admins';

    public $adminid;
    public $username;
    public $email;
    public $password;
    public $created_at;
    public $updated_at;
    public $deleted_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function isEmailValid()
    {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    public function isRegistered()
    {
        $query = "SELECT adminid FROM " . $this->table . " 
                    WHERE 
                        email = :email 
                        AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        return $stmt;
    }

    public function checkPassword($old_password)
    {
        $query = "SELECT adminid FROM " . $this->table . " 
                    WHERE 
                        password = :password 
                        AND email = :email";
        $stmt = $this->conn->prepare($query);
        $old_password = md5(htmlspecialchars(strip_tags($old_password)));
        $stmt->bindParam(':password', $old_password);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        return $stmt;
    }

    public function registerAdmin()
    {
        $query = 'INSERT INTO ' . $this->table . '
                    SET
                        email = :email,
                        username= :username,
                        password = :password,
                        updated_at = CURRENT_TIMESTAMP()';
        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = md5(htmlspecialchars(strip_tags($this->password)));
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':username', $this->username);
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

    public function isLoggedIn()
    {
        // return (isset($_SESSION['id'])) ? true : false;
        return false;
    }

    public function loginAdmin()
    {
        // $query = "SELECT * FROM " . $this->table . " 
        //             WHERE email = :email 
        //             AND password = :password 
        //             AND deleted_at IS NULL";
        $query = "SELECT * FROM " . $this->table . " 
                    WHERE email = :email 
                    AND password = :password 
                    ";
        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = md5(htmlspecialchars(strip_tags($this->password)));
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->execute();
        return $stmt;
    }

    public function getAdminDetails()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE adminid = :adminid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":adminid", $this->adminid);
        $stmt->execute();
        return $stmt;
    }

    public function updateAdminDetails()
    {
        // $id = $_SESSION['id'];
        $adminid =  $this->adminid;
        $query = "UPDATE " . $this->table . " 
                    SET 
                        username = :username, 
                        updated_at = CURRENT_TIMESTAMP() 
                            WHERE adminid = :adminid";
        $stmt = $this->conn->prepare($query);
        $this->username = htmlspecialchars(strip_tags($this->username));
        $stmt->bindParam(":adminid", $adminid);
        $stmt->bindParam(":username", $this->username);
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

    public function updateAdminPassword()
    {
        // $id = $_SESSION['id'];
        $id =  $this->adminid;
        $query = "UPDATE " . $this->table . " 
                    SET 
                        password = :password, 
                        updated_at = CURRENT_TIMESTAMP() 
                            WHERE adminid = :id";
        $stmt = $this->conn->prepare($query);
        $this->password = md5(htmlspecialchars(strip_tags($this->password)));
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":password", $this->password);
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

    public function deleteAdmin()
    {
       // $id = $_SESSION['id'];
       $id =  $this->adminid;
        $query = "UPDATE " . $this->table . " 
                    SET 
                        deleted_at = CURRENT_TIMESTAMP() 
                            WHERE adminid = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
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
?>