<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/temperature.php';
  
// instantiate database and temperature object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$temperature = new temperature($db);
  
// query temperatures
$stmt = $temperature->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // temperatures array
    $temperatures_arr=array();
    $temperatures_arr["records"]=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $temperature_item=array(
            "Location" => $Location,
            "SampleTime" => $SampleTime,
            "Air" => $Air,
            "Water" => $Water,
            "ControlBox" => $ControlBox,
            "TimeStamp" => $TimeStamp

        );
  
        array_push($temperatures_arr["records"], $temperature_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show temperatures data in json format
    echo json_encode($temperatures_arr);
}
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no temperature measurements found
    echo json_encode(
        array("message" => "No temperature measurements found.")
    );
}