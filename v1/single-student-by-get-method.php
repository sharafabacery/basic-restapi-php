<?php
//debguer
ini_set("display_errors",1);
header("Access-Control-Allow-Origin: *");//every request can access this api
//header("Content-type: application/json; charset: UTF-8");
header("Access-Control-Allow-Methods: GET");//method type

include_once('../config/database.php');
include_once('../classes/student.php');

$db=new Database();

$connection=$db->connect();

$student=new Student($connection);
if ($_SERVER['REQUEST_METHOD'] === "GET") {
   
  $param=isset($_GET['id'])?intval($_GET['id']):"";

  if (!empty($param)) {
    $student->id=$param;
    $student_data= $student->get_single_student();
    if (!empty($student_data)) {
        http_response_code(200);
        echo json_encode(array("status"=>1,"data"=>$student_data));
    }else{
        http_response_code(404);
        echo json_encode(array("status"=>0,"message"=>"student not found"));
        
    }
  }

}else{
    http_response_code(503);//service is not here
    echo json_encode(array("status"=>0,"message"=>"Acess denied"));
}

?>