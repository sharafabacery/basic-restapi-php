<?php

require '../vendor/autoload.php';
use \Firebase\JWT\JWT;


header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods:GET");
header("Content-type: application/json; charst=UTF-8");


include_once("../config/database.php");
include_once("../classes/User.php");

$db=new Database();
$connection=$db->connect();
$user_obj=new Users($connection);

if ($_SERVER['REQUEST_METHOD']==="GET") {
    $headers=getallheaders();

    $jwt=$headers['Authorization'];
   try {

    $secret_key="mama 7lwa";

    $decoded_data=JWT::decode($jwt,$secret_key,['HS256']);
    $user_obj->user_id=$decoded_data->data->id;
    $projects=$user_obj->get_user_projects();

    
    //print_r($projects);

    if ($projects->num_rows>0) {
        $projects_array=[];
        while ($row=$projects->fetch_assoc()) {
            $projects_array[]=["id"=>$row['id'],"name"=>$row["name"],"description"=>$row["description"],"user_id"=>$row["user_id"],"created_at"=>$row["created_at"],"status"=>$row["status"]];

        }
        http_response_code(200);
        echo json_encode(["status"=>1,"projects"=>$projects_array]);
    }else{
        http_response_code(404);
        echo json_encode(["status"=>0,"message"=>"no data found"]);
    }


    } catch (Exception $ex) {
        http_response_code(500);
        echo json_encode(["status"=>0,"message"=>$ex->getMessage()]);
    }
    
}

?>