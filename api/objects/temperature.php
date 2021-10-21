<?php

// ini_set("log_errors", 1);
// ini_set("error_log", "errorlog.txt");
// error_log( "Hello, errors!" );

class temperature{
  
    // database connection and table name
    private $conn;
    private $table_name = "wp_WindIOT_Temperatures";
  
    // object properties
    public $location;
    public $SampleTime;
    public $Air;
    public $Water;
    public $ControlBox;
    public $TimeStamp;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

// read temperatures
function read(){
  
    // select all query
    $query = "SELECT
                Location, SampleTime, Air, Water, ControlBox, TimeStamp  
            FROM
                " . $this->table_name . " p
            ORDER BY
                SampleTime DESC
                LIMIT 144";
  
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
                Location=:Location, SampleTime=:SampleTime, Air=:Air, Water=:Water, ControlBox=:ControlBox, TIMESTAMP=:TimeStamp";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->Location=htmlspecialchars(strip_tags($this->Location));
    $this->SampleTime=htmlspecialchars(strip_tags($this->SampleTime));
    $this->Air=htmlspecialchars(strip_tags($this->Air));
    $this->Water=htmlspecialchars(strip_tags($this->Water));
    $this->ControlBox=htmlspecialchars(strip_tags($this->ControlBox));
    $this->TimeStamp=htmlspecialchars(strip_tags($this->TimeStamp));
  
    // bind values
    $stmt->bindParam(":Location", $this->Location);
    $stmt->bindParam(":SampleTime", $this->SampleTime);
    $stmt->bindParam(":Air", $this->Air);
    $stmt->bindParam(":Water", $this->Water);
    $stmt->bindParam(":ControlBox", $this->ControlBox);
    $stmt->bindParam(":TimeStamp", $this->TimeStamp);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
      
}

}
?>