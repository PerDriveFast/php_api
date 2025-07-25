<?php

class Catagory
{

    //db stuff
    private $conn;
    private $table = 'categories';

    //category properties
    public $id;
    public $name;
    public $create_at;

    //constructor
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    //getting posts from database
    public function read()
    {
        //create query
        $query = 'SELECT c.id, c.name, c.create_at
                  FROM ' . $this->table;

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt->execute();

        return $stmt;
    }
}
