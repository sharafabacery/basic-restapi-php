<?php

header("Access-Control-Allow-Origin: *");//every request can access this api
header("Content-type: application/json; charset: UTF-8");
header("Access-Control-Allow-Methods: POST");//method type

include_once('../config/database.php');
include_once('../classes/student.php');

$db=new Database();

$connection=$db->connect();

$student=new Student($connection);
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $data=json_decode(file_get_contents("php://input"));//get data from body
    if (!empty($data->name)&&!empty($data->email)&&!empty($data->mobile)) {
        $student->name=$data->name;
        $student->email=$data->email;
        $student->mobile=$data->mobile;

        if ($student->create_data()) {
        http_response_code(200);
        echo json_encode(array("status"=>1,"message"=>"Student has been created"));
        }else{
            http_response_code(500);
        echo json_encode(array("status"=>0,"message"=>"Failed to insert data"));
        
            
        }
    }else{
        http_response_code(404);//service is not here
        echo json_encode(array("status"=>0,"message"=>"all values needed"));
      
    }
}else{      http_response_code(503);//service is not here
    echo json_encode(array("status"=>0,"message"=>"Acess denied"));
  }
?>