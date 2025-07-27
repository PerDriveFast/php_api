<?php

class Post
{

    //db stuff
    private $conn;
    private $table = 'posts';

    //post properties
    public $id;
    public $category_id;
    public $category_name;
    public $title;

    public $body;
    public $author;
    public $create_at;

    // register properties
    public $first_name;
    public $last_name;
    public $email;
    public $password;



    //constructor
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    //getting posts from database
    public function read()
    {
        //create query
        $query = 'SELECT c.name as category_name, p.id, p.category_id, p.title, p.body, p.author, p.create_at
                  FROM ' . $this->table . ' p
                  LEFT JOIN categories c ON p.category_id = c.id
                  ORDER BY p.create_at DESC';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt->execute();

        return $stmt;
    }

    public function read_single()
    {
        //create query
        $query = 'SELECT c.name as category_name, p.id, p.category_id, p.title, p.body, p.author, p.create_at
                  FROM ' . $this->table . ' p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.id = ?
                  LIMIT 0,1';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //bind id
        $stmt->bindParam(1, $this->id);

        //execute query
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->category_name = $row['category_name'];
        $this->title = $row['title'];
        $this->body = $row['body'];
        $this->author = $row['author'];
        $this->create_at = $row['create_at'];
        $this->category_id = $row['category_id'];



        return $stmt;
    }

    public function create()
    {
        //create query
        $query = 'INSERT INTO ' . $this->table . '
                  SET category_id = :category_id, title = :title, body = :body, author = :author, create_at = :create_at';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //sanitize
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->create_at = date('Y-m-d H:i:s');

        //bind data
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':create_at', $this->create_at);

        //execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update()
    {
        //create query
        $query = 'UPDATE posts 
          SET title = :title, body = :body, author = :author, create_at = :create_at 
          WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->create_at = !empty($this->create_at) ? $this->create_at : date('Y-m-d H:i:s');
        $this->id = intval($this->id); // ensure it's integer

        // bind
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':create_at', $this->create_at);
        $stmt->bindParam(':id', $this->id);



        //execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        //create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        //bind id
        $stmt->bindParam(':id', $this->id);

        //execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function readUsers()
    {
        $query = "SELECT id, first_name, last_name, email, password FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function updateUser()
    {
        $query = "UPDATE users SET 
                    first_name = :first_name, 
                    last_name = :last_name, 
                    email = :email, 
                    password = :password
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
    }
}
