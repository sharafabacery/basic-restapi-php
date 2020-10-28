<?php
class Users{

    public $name;
    public $email;
    public $password;
    public $user_id;
    public $project_name;
    public $description;
    public $status;
    
    private $conn;
    private $users_tbl;
    private $projects_tbl;

    public function __construct($db){
      $this->conn=$db;
      $this->users_tbl="tbl_users";
      $this->projects_tbl="tbl_projects";
      
    }
    
    public function create_user(){
        $user_query="insert into ".$this->users_tbl." set name = ? ,email = ?,password = ?";
        
        $user_obj=$this->conn->prepare($user_query);

        $user_obj->bind_param("sss",$this->name,$this->email,$this->password);

        if ($user_obj->execute()) {
            return true;
        }else{
            return false;
        }
    }

    public function check_email(){
        $email_query="select  * from ".$this->users_tbl. " where email = ?";
       
        $user_obj=$this->conn->prepare($email_query);

        $user_obj->bind_param("s",$this->email);

        if ($user_obj->execute()) {
            
            $data=$user_obj->get_result();
            
            return $data->fetch_assoc();
        }

        return array();

    }

    public function check_login(){
        $email_query="select  * from ".$this->users_tbl. " where email = ? ";
       
        $user_obj=$this->conn->prepare($email_query);

        $user_obj->bind_param("s",$this->email);

        if ($user_obj->execute()) {
            
            $data=$user_obj->get_result();
            
            return $data->fetch_assoc();
        }

        return array();
    }
    public function create_project(){
        $project_query= "insert into ".$this->projects_tbl. " set user_id=? , name=? , description=?, status= ?";
        
        $project_obj=$this->conn->prepare($project_query);

        $project_name=htmlspecialchars(strip_tags($this->user_id));
        $description=htmlspecialchars(strip_tags($this->description));
        $status=htmlspecialchars(strip_tags($this->status));
        
        $project_obj->bind_param("isss",$this->user_id,$project_name,$description,$status);
        
        if ($project_obj->execute()) {
            return true;
        }else{
            return false;
        }
    }
    
    public function get_all_projects(){
        $project_query="select * from ".$this->projects_tbl. " order by id desc";
        
        $project_obj=$this->conn->prepare($project_query);

        $project_obj->execute();

        return $project_obj->get_result();
    }
    
    public function get_user_projects(){
        $project_query="select * from ".$this->projects_tbl. " where  user_id=? order by id desc";
        
        $project_obj=$this->conn->prepare($project_query);
       
        $project_obj->bind_param("i",$this->user_id);
       
        $project_obj->execute();

        return $project_obj->get_result();
    }
}


?>