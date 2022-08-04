<?php
class pressure{
  
    // database connection and table name
    private $conn;
    private $table_name = "wp_WindIOT_PressureWave";
  
    // object properties
    public $location;
    public $SampleTime;
    public $AbsPressPa;
    public $SigWaveHtFt;
    public $BiggestWaveFt;
    public $CountWave;
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
                Location, SampleTime, AbsPressPa, SigWaveHtFt, BiggestWaveFt, CountWave, TimeStamp  
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
                Location=:Location, SampleTime=:SampleTime, AbsPressPa=:AbsPressPa, SigWaveHtFt=:SigWaveHtFt, BiggestWaveFt=:BiggestWaveFt, CountWave=:CountWave, TimeStamp=:TimeStamp";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->Location=htmlspecialchars(strip_tags($this->Location));
    $this->SampleTime=htmlspecialchars(strip_tags($this->SampleTime));
    $this->AbsPressPa=htmlspecialchars(strip_tags($this->AbsPressPa));
    $this->SigWaveHtFt=htmlspecialchars(strip_tags($this->SigWaveHtFt));
    $this->BiggestWaveFt=htmlspecialchars(strip_tags($this->BiggestWaveFt));
    $this->CountWave=htmlspecialchars(strip_tags($this->CountWave));
    $this->TimeStamp=htmlspecialchars(strip_tags($this->TimeStamp));
  
    // bind values
    $stmt->bindParam(":Location", $this->Location);
    $stmt->bindParam(":SampleTime", $this->SampleTime);
    $stmt->bindParam(":AbsPressPa", $this->AbsPressPa);
    $stmt->bindParam(":SigWaveHtFt", $this->SigWaveHtFt);
    $stmt->bindParam(":BiggestWaveFt", $this->BiggestWaveFt);
    $stmt->bindParam(":CountWave", $this->CountWave);
    $stmt->bindParam(":TimeStamp", $this->TimeStamp);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
      
}

}
?>