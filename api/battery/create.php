<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate battery object
include_once '../objects/battery.php';
  
$database = new Database();
$db = $database->getConnection();
  
$battery = new battery($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->site) &&
    !empty($data->Time) &&
    !empty($data->SOC)
){
  
    // set battery property values
    $battery->Location = $data->site;
    $battery->SampleTime = $data->Time;
    $battery->StateOfCharge = $data->SOC;
    $battery->TimeStamp = date('Y-m-d H:i:s');
    $battery->ResetsCntr = $data->ResetsCntr;
    $battery->AngleFail = $data->AngleFail;
    $battery->CompassFail = $data->CompassFail;
    $battery->TempAirFail = $data->TempAirFail;
    $battery->TempWaterFail = $data->TempWaterFail;
    $battery->TempBoxFail = $data->TempBoxFail;
    $battery->PressureFail = $data->PressureFail;

  
    // create the battery
    if($battery->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "battery was created."));
    }
  
    // if unable to create the battery, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create battery."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create battery. Data is incomplete."));
}
?>