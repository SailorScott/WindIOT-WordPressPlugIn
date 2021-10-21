<?php
class TestJSON{
  
    // database connection and table name
    private $conn;
    private $table_name = "wp_WindIOT_RawJSON";
  
    // object properties
    public $JSON;
    public $TimeStamp;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

// read RawJSONs
function read(){
  
    // select all query
    $query = "SELECT
                ID, JSON, TimeStamp  
            FROM
                " . $this->table_name . " p
            ORDER BY
                TimeStamp DESC";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
}

// create product
function create(){
  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                JSON=:JSON, TimeStamp=:TimeStamp";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
   // $this->JSON=htmlspecialchars(strip_tags($this->JSON));
    $this->JSON=$this->JSON;
    $this->TimeStamp=htmlspecialchars(strip_tags($this->TimeStamp));
  
    // bind values
    $stmt->bindParam(":JSON", $this->JSON);
    $stmt->bindParam(":TimeStamp", $this->TimeStamp);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
      
}

}
?>