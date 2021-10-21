<?php
class wind{
  
    // database connection and table name
    private $conn;
    private $table_name = "wp_WindIOT_Wind";
  
    // object properties
    public $location;
    public $SampleTime;
    public $Speed;
    public $Gust;
    public $Direction;
    public $TimeStamp;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

// read winds
function read(){
  
    $readSince = 1624665600;

    // select all query
    $query = "SELECT
                Location, SampleTime, Speed, GUST as Gust, Direction, TimeStamp  
            FROM
                " . $this->table_name . " 
           WHERE SampleTime > " . $readSince . "
           ORDER BY
                SampleTime DESC";
  
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
                Location=:Location, SampleTime=:SampleTime, Speed=:Speed, GUST=:Gust, Direction=:Direction, TimeStamp=:TimeStamp";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->Location=htmlspecialchars(strip_tags($this->Location));
    $this->SampleTime=htmlspecialchars(strip_tags($this->SampleTime));
    $this->Speed=htmlspecialchars(strip_tags($this->Speed));
    $this->Gust=htmlspecialchars(strip_tags($this->Gust));
    $this->Direction=htmlspecialchars(strip_tags($this->Direction));
    $this->TimeStamp=htmlspecialchars(strip_tags($this->TimeStamp));
  
    // bind values
    $stmt->bindParam(":Location", $this->Location);
    $stmt->bindParam(":SampleTime", $this->SampleTime);
    $stmt->bindParam(":Speed", $this->Speed);
    $stmt->bindParam(":Gust", $this->Gust);
    $stmt->bindParam(":Direction", $this->Direction);
    $stmt->bindParam(":TimeStamp", $this->TimeStamp);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
      
}

}
?>