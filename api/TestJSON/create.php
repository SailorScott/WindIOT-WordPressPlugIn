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
include_once '../objects/TestJSON.php';
  
$database = new Database();
$db = $database->getConnection();
  
$TestJSON = new TestJSON($db);
  
// get posted data
$data = file_get_contents("php://input");
  
// make sure data is not empty
if(
    !empty($data)
){
  
    // set TestJSON property values
    $TestJSON->JSON = strval($data);
     $TestJSON->TimeStamp = date('Y-m-d H:i:s');
  
    // create the TestJSON
    if($TestJSON->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "TestJSON was created."));
    }
  
    // if unable to create the TestJSON, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create TestJSON."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create TestJSON. Data is incomplete."));
}
?>