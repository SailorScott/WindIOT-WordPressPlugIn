<?php
class Battery{
  
    // database connection and table name
    private $conn;
    private $table_name = "wp_WindIOT_Battery";
  
    // object properties
    public $location;
    public $SampleTime;
    public $StateOfCharge;
    public $TimeStamp;

    public $ResetsCntr;
    public $AngleFail;
    public $CompassFail;
    public $TempAirFail;
    public $TempWaterFail;
    public $TempBoxFail;
    public $PressureFail;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

// read batterys
function read(){
  
    // select all query
    $query = "SELECT
                Location, SampleTime,StateOfCharge, TimeStamp, ResetsCntr, AngleFail, CompassFail, TempAirFail, TempWaterFail, TempBoxFail, PressureFail  
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
                Location=:Location
                , SampleTime=:SampleTime
                , StateOfCharge=:StateOfCharge
                , TimeStamp=:TimeStamp
                , ResetsCntr =:ResetsCntr
                , AngleFail =:AngleFail
                , CompassFail =:CompassFail
                , TempAirFail =:TempAirFail
                , TempWaterFail =:TempWaterFail
                , TempBoxFail =:TempBoxFail
                , PressureFail =:PressureFail";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->Location=htmlspecialchars(strip_tags($this->Location));
    $this->SampleTime=htmlspecialchars(strip_tags($this->SampleTime));
    $this->StateOfCharge=htmlspecialchars(strip_tags($this->StateOfCharge));
    $this->TimeStamp=htmlspecialchars(strip_tags($this->TimeStamp));
  
    $this->ResetsCntr = htmlspecialchars(strip_tags($this->ResetsCntr));
    $this->AngleFail = htmlspecialchars(strip_tags($this->AngleFail));
    $this->CompassFail = htmlspecialchars(strip_tags($this->CompassFail));
    $this->TempAirFail = htmlspecialchars(strip_tags($this->TempAirFail));
    $this->TempWaterFail = htmlspecialchars(strip_tags($this->TempWaterFail));
    $this->TempBoxFail = htmlspecialchars(strip_tags($this->TempBoxFail));
    $this->PressureFail = htmlspecialchars(strip_tags($this->PressureFail));
    // bind values
    $stmt->bindParam(":Location", $this->Location);
    $stmt->bindParam(":SampleTime", $this->SampleTime);
    $stmt->bindParam(":StateOfCharge", $this->StateOfCharge);
    $stmt->bindParam(":TimeStamp", $this->TimeStamp);
  
    $stmt->bindParam(":ResetsCntr", $this->ResetsCntr);
    $stmt->bindParam(":AngleFail", $this->AngleFail);
    $stmt->bindParam(":CompassFail", $this->CompassFail);
    $stmt->bindParam(":TempAirFail", $this->TempAirFail);
    $stmt->bindParam(":TempWaterFail", $this->TempWaterFail);
    $stmt->bindParam(":TempBoxFail", $this->TempBoxFail);
    $stmt->bindParam(":PressureFail", $this->PressureFail);
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
      
}

}
?>