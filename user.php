<?php
class User
{

    private $conn;
    private $dbTable = 'users';

    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $create_date;
    public $update_date;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // get all users
    function read()
    {
        $query = "SELECT * FROM $this->dbTable";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // get user by id
    function readById()
    {
        $query = "SELECT * FROM " . $this->dbTable . " WHERE id= ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->first_name = $row['first_name'];
        $this->last_name = $row['last_name'];
        $this->email = $row['email'];
        $this->create_date = $row['create_date'];
        $this->update_date = $row['update_date'];
    }

    // add new user
    function create()
    {
        // create query
        $query = "INSERT INTO " . $this->dbTable . " SET
                first_name=:first_name,
                last_name=:last_name,
                email=:email,
                create_date=:create_date";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // clear
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->create_date = htmlspecialchars(strip_tags($this->create_date));

        // bind params
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":create_date", $this->create_date);

        // exec query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // edit user
    function update()
    {
        // create query 
        $query = "UPDATE " . $this->dbTable . " SET
                first_name=:first_name,
                last_name=:last_name,
                email=:email
            WHERE
                id = :id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // clear 
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind params
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(':id', $this->id);

        // exec query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function filter(
        $f_id,
        $f_first_name,
        $f_last_name,
        $f_email,
        $from_create_date,
        $to_create_date,
        $from_update_date,
        $to_update_date
    ) {
        $query = "SELECT * FROM " . $this->dbTable . "
                    WHERE
                    id LIKE ? OR 
                    first_name LIKE ? OR 
                    last_name LIKE ? OR
                    email LIKE ? OR
                    create_date BETWEEN ? AND ? OR
                    update_date BETWEEN ? AND ?
                ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $f_id);
        $stmt->bindParam(2, $f_first_name);
        $stmt->bindParam(3, $f_last_name);
        $stmt->bindParam(4, $f_email);
        $stmt->bindParam(5, $from_create_date);
        $stmt->bindParam(6, $to_create_date);
        $stmt->bindParam(7, $from_update_date);
        $stmt->bindParam(8, $to_update_date);

        $stmt->execute();

        return $stmt;
    }

    function search($keywords)
    {
        $query = "SELECT * FROM " . $this->dbTable . "
                    WHERE
                    first_name LIKE ? OR 
                    last_name LIKE ? OR
                    email LIKE ? ";
        $stmt = $this->conn->prepare($query);

        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        $stmt->execute();

        return $stmt;
    }
}
