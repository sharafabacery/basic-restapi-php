<?php
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods:POST");
header("Content-type: application/json; charst=UTF-8");


include_once("../config/database.php");
include_once("../classes/User.php");

$db=new Database();
$connection=$db->connect();
$user_obj=new Users($connection);

if ($_SERVER['REQUEST_METHOD']==="POST") {

    //$data=json_decode(file_get_contents("php://input"));
    $all_headers =getallheaders();
    $jwt=$all_headers['Authorization'];
   // $jwt=explode(" ",$all_headers['Authorization'])[1];
    
   
    if (!empty($jwt)) {
        try {
            $secret_key="mama 7lwa";
        
            $decoded_data=JWT::decode($jwt,$secret_key,['HS256']);
            
            $user_id=$decoded_data->data->id;

            http_response_code(200);
            echo json_encode(["status"=>1,"message"=>"We got JWT Token","user_data"=>$decoded_data,"user_id"=>$user_id]);
        
        } catch (Exception $th) {
            http_response_code(500);
            echo json_encode(["status"=>0,"message"=>$ex->getMessage()]);
            
        }
       }



}




?>