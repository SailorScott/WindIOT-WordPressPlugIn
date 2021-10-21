<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/battery.php';
  
// instantiate database and battery object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$battery = new Battery($db);
  
// query batterys
$stmt = $battery->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // batterys array
    $batterys_arr=array();
    $batterys_arr["records"]=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $battery_item=array(
            "Location" => $Location,
            "SampleTime" => $SampleTime,
            "StateOfCharge" => $StateOfCharge,
            "TimeStamp" => $TimeStamp,
            "ResetsCntr" => $ResetsCntr,
            "AngleFail" => $AngleFail,
            "CompassFail" => $CompassFail,
            "TempAirFail" => $TempAirFail,
            "TempWaterFail" => $TempWaterFail,
            "TempBoxFail" => $TempBoxFail
        );
  
        array_push($batterys_arr["records"], $battery_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show batterys data in json format
     echo json_encode($batterys_arr);
   
}
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no battery measurements found
    echo json_encode(
        array("message" => "No battery measurements found.")
    );
}