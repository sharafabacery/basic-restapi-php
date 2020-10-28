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
    
    $data=json_decode(file_get_contents("php://input"));
    if (!empty($data->email)&&!empty($data->password)) {
        $user_obj->email=$data->email;
        //$user_obj->password= $data->password;

        $user_data=$user_obj->check_login();
        if ($user_data) {
            $name=$user_data["name"];
            $email=$user_data["email"];
            $password=$user_data["password"];
            
            if (password_verify($data->password,$password)) {
                $iss="localhost";//where it live host
                $iat=time();//init created at ??
                $nbf=$iat+10;// wait until valid to other api
                $exp=$iat+(60*60);
                $aud="myusers";//who using this
                $user_arr_data=array(
                    "id"=>$user_data["id"],
                    "name"=>$user_data["name"],
                    "email"=>$user_data["email"],
                );
                $payload_info=array(
                    "iss"=>$iss,
                    "iat"=>$iat,
                    "nbf"=>$nbf,
                    "exp"=>$exp,
                    "aud"=>$aud,
                    "data"=>$user_arr_data,
                );
                $secret_key="mama 7lwa";
                //payload , secret
                $jwt=JWT::encode($payload_info,$secret_key);

                http_response_code(200);//service is not here
                echo json_encode(array("status"=>1,"message"=>"User logged in successfully","jwt"=>$jwt));
            }else{
                http_response_code(404);//service is not here
                echo json_encode(array("status"=>0,"message"=>"invalid crediatials"));
            }
        }else{
            http_response_code(404);//service is not here
            echo json_encode(array("status"=>0,"message"=>"invalid crediatials"));
        }

    }else{
        http_response_code(404);//service is not here
        echo json_encode(array("status"=>0,"message"=>"all values needed"));
    }
}


?>