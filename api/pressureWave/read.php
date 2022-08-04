<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/pressure.php';
  
// instantiate database and pressure object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$pressure = new pressure($db);
  
// query pressures
$stmt = $pressure->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // pressures array
    $pressures_arr=array();
    $pressures_arr["records"]=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $pressure_item=array(
            "Location" => $Location,
            "SampleTime" => $SampleTime,
            "AbsPressPa" => $AbsPressPa,
            "SigWaveHtFt" => $SigWaveHtFt,
            "BiggestWaveFt" => $BiggestWaveFt,
            "CountWave" => $CountWave,
            "TimeStamp" => $TimeStamp

        );
  
        array_push($pressures_arr["records"], $pressure_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show pressures data in json format
    echo json_encode($pressures_arr);
}
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no pressure measurements found
    echo json_encode(
        array("message" => "No pressure measurements found.")
    );
}