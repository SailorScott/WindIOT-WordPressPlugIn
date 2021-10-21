<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate temperature object
include_once '../objects/temperature.php';
  
$database = new Database();
$db = $database->getConnection();
  
$temperature = new temperature($db);
  
// get posted data 
//{"site":"PYC","temp":[{"Time":1585093728,"Air":3.8, "Box":6.2, "Water":4.4}]}
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->site) &&
    !empty($data->temp) 
){
    // set temperature property values
    $temperature->Location = $data->site;
    $temperature->TimeStamp = date('Y-m-d H:i:s');
 
    $saveDBresult = false;

    foreach($data->temp as $key => $value) {

        $temperature->SampleTime = $value->Time;
        $temperature->Air = $value->Air;
        $temperature->ControlBox = $value->Box;
        $temperature->Water = $value->Water;

        $saveDBresult = $temperature->create();

  
    }
  //  $thisFar = "after for";
  
       if($saveDBresult){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "temperature was created."));
    }
  
    // if unable to create the temperature, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create temperature."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create temperature. Data is incomplete."));
}
?>