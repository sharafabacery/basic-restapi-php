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
   $data=$student->get_all_data();
   if ($data->num_rows>0) {
       //we have data inside table
       $students["records"]=[];
       while ($row=$data->fetch_assoc()) {
       array_push( $students["records"],array(
           "id"=>$row['id'],
           "name"=>$row['name'],
           "email"=>$row['email'],
           "mobile"=>$row['mobile'],
           "status"=>$row['status'],
           "created_at"=>date("Y-m-d",strtotime( $row['created_at']))
       ));        
       }
       http_response_code(200);
       echo json_encode(["status"=>0,"message"=>$students["records"]]);
   }


}else{
    http_response_code(503);//service is not here
    echo json_encode(array("status"=>0,"message"=>"Acess denied"));
}

?>