<?php

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods:POST");
header("Content-type: application/json; charst=UTF-8");


include_once("../config/database.php");
include_once("../classes/User.php");

$db=new Database();
$connection=$db->connect();
$user_obj=new Users($connection);

if ($_SERVER['REQUEST_METHOD']==="POST") {
    $data=json_decode(file_get_contents("php://input"));
    if (!empty($data->name)&&!empty($data->email)&&!empty($data->password)) {
        $user_obj->name=$data->name;
        $user_obj->email=$data->email;
        $user_obj->password=password_hash($data->password,PASSWORD_DEFAULT);

        $email_data=$user_obj->check_email();
        if (!empty($email_data)) {
            http_response_code(500);
            echo json_encode(array("status"=>0,"message"=>"user already exists,try anthor email address"));           
        }else{
            if ($user_obj->create_user()) {
                http_response_code(200);//service is not here
                echo json_encode(array("status"=>1,"message"=>"user has been created"));
            }else{
                http_response_code(500);//service is not here
                echo json_encode(array("status"=>0,"message"=>"failed to save user"));
            }
    
        }
        

    }else{
        http_response_code(500);//service is not here
        echo json_encode(array("status"=>0,"message"=>"all values needed"));
    }
}else{
    http_response_code(503);//service is not here
    echo json_encode(array("status"=>0,"message"=>"Acess denied"));
}







?>