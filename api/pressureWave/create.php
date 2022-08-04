<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate pressure and wave object
include_once '../objects/pressure.php';
  
$database = new Database();
$db = $database->getConnection();
  
$pressure = new pressure($db);
  
// get posted data 
// {"site":"PYC","pressureWave":[{"Time":1658971570,"AbsPressPa":87695.4, "SigWaveHtFt":6.0, "BiggestWaveFt":6.0, "CountWave":3},...]}
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->site) &&
    !empty($data->pressureWave) 
){
    // set pressure property values
    $pressure->Location = $data->site;
    $pressure->TimeStamp = date('Y-m-d H:i:s');
 
    $saveDBresult = false;

    foreach($data->pressureWave as $key => $value) {

        $pressure->SampleTime = $value->Time;
        $pressure->AbsPressPa = $value->AbsPressPa;
        $pressure->SigWaveHtFt = $value->SigWaveHtFt;
        $pressure->BiggestWaveFt = $value->BiggestWaveFt;
        $pressure->CountWave = $value->CountWave;

        $saveDBresult = $pressure->create();

  
    }
  //  $thisFar = "after for";
  
       if($saveDBresult){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "pressure and wave was created."));
    }
  
    // if unable to create the pressure, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create pressure and wave."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create pressure and wave. Data is incomplete."));
}
?>