<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate wind object
include_once '../objects/wind.php';
  
$database = new Database();
$db = $database->getConnection();
  
$wind = new wind($db);
  
// get posted data
//{"site":"PYC","wind":[{"Time":1585093128,"Spd":0.0,"Gst":0.0,"Dir":119},{"Time":1585093728,"Spd":0.0,"Gst":0.3,"Dir":134}]}
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->site) &&
    !empty($data->wind)
){
  
    // set wind property values
    $wind->Location = $data->site;
    $wind->TimeStamp = date('Y-m-d H:i:s');
   
    $saveDBresult = false;

    foreach($data->wind as $key => $value) {
        $wind->SampleTime = $value->Time;
        $wind->Speed = $value->Spd;
        $wind->Gust = $value->Gst;
        $wind->Direction = $value->Dir;

        $saveDBresult = $wind->create();

    }
  
       if($saveDBresult){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "wind was created."));
    }
  
    // if unable to create the wind, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create wind."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create wind. Data is incomplete."));
}
?>