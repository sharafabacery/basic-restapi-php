<?php
class Student{
    public $name;
    public $email;
    public $mobile;
    public $id;

    private $conn;
    private $table_name;

    public function __construct($db){
        $this->conn=$db;
        $this->table_name="tbl_students";
    }

    public function create_data(){
        
        $query="insert into ".$this->table_name.
        " set name=?, email=?, mobile=? ";

        $obj=$this->conn->prepare($query);

        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->mobile=htmlspecialchars(strip_tags($this->mobile));
        
        $obj->bind_param("sss",$this->name,$this->email,$this->mobile);

        if ($obj->execute()) {
            return true;
        }else{
            return false;
        }
    }
    //
    public function get_all_data(){
        $sql_query="select * from ".$this->table_name;

        $std_obj=$this->conn->prepare($sql_query);

        if ($std_obj->execute()) {
            return $std_obj->get_result();//get all data 
        }

    }
    public function get_single_student(){
        $sql="select * from ".$this->table_name." where id=?";

        $obj=$this->conn->prepare($sql);

        $obj->bind_param("i",$this->id);

        $obj->execute();

        $data=$obj->get_result();

        return $data->fetch_assoc();//return all data
    }

    public function update_student(){
        $update_query="update ".$this->table_name ." set  name=? ,email=? ,mobile=? where id=? ";

        $query_obj=$this->conn->prepare($update_query);

        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->mobile=htmlspecialchars(strip_tags($this->mobile));
        $this->id=htmlspecialchars(strip_tags($this->id));

        $query_obj->bind_param("sssi",$this->name,$this->email,$this->mobile,$this->id);
        
        if ($query_obj->execute()&&$query_obj->affected_rows>0) {
            return true;
        }else{
            return false;
        }
    }

    public function delete_student(){
        $delete_query="delete from ".$this->table_name." where id=?";

        $query_obj=$this->conn->prepare($delete_query);

        $this->id=htmlspecialchars(strip_tags($this->id));

        $query_obj->bind_param("i",$this->id);

        if ($query_obj->execute()&&$query_obj->affected_rows>0) {
           
            return true;
        }else{
           
            return false;
        }
    
    }
}
?>