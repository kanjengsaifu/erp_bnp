<?php
require_once("BaseModel.php");

class CustomerModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
            mysqli_set_charset(static::$db,"utf8");
        }
    }

    function getLogin($username, $password){
        $username = static::$db->real_escape_string($username);
        $password = static::$db->real_escape_string($password);

        if ($result = mysqli_query(static::$db,"SELECT *
            FROM tb_customer 
            WHERE customer_username = '$username' 
            AND customer_password = '$password'", MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getCustomerBy($keyword=''){
        $sql = "SELECT * 
        FROM tb_customer 
        WHERE (customer_code LIKE ('%$keyword%') OR customer_name LIKE ('%$keyword%'))
        ORDER BY customer_name
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getCustomerByCode($code){
        $sql = "SELECT * 
        FROM tb_customer 
        WHERE customer_code = '$code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }
    }

    function countCustomerByUsername($username){
        $sql = "SELECT *
        FROM tb_customer 
        WHERE customer_username = '$username' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data=[];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getCustomerLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(RIGHT(customer_code,3) AS SIGNED)),0) + 1,$digit,'0' )) AS lastcode 
        FROM tb_customer
        WHERE customer_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function updateProfileCustomerByCode($code,$data = []){
        $data['customer_name']=mysqli_real_escape_string(static::$db,$data['customer_name']);
        $data['customer_image']=mysqli_real_escape_string(static::$db,$data['customer_image']);
        $data['customer_tax']=mysqli_real_escape_string(static::$db,$data['customer_tax']);
        $data['customer_phone']=mysqli_real_escape_string(static::$db,$data['customer_phone']);
        $data['customer_email']=mysqli_real_escape_string(static::$db,$data['customer_email']);
        $data['customer_address']=mysqli_real_escape_string(static::$db,$data['customer_address']);
        $data['customer_amphur']=mysqli_real_escape_string(static::$db,$data['customer_amphur']);
        $data['customer_district']=mysqli_real_escape_string(static::$db,$data['customer_district']);
        $data['customer_province']=mysqli_real_escape_string(static::$db,$data['customer_province']);
        $data['customer_zipcode']=mysqli_real_escape_string(static::$db,$data['customer_zipcode']);
        $data['customer_username']=mysqli_real_escape_string(static::$db,$data['customer_username']);
        $data['customer_password']=mysqli_real_escape_string(static::$db,$data['customer_password']);
        
        $sql = "UPDATE tb_customer SET 
        customer_name = '".$data['customer_name']."', 
        customer_image = '".$data['customer_image']."', 
        customer_tax = '".$data['customer_tax']."', 
        customer_phone = '".$data['customer_phone']."', 
        customer_email = '".$data['customer_email']."', 
        customer_address = '".$data['customer_address']."',
        customer_amphur = '".$data['customer_amphur']."',
        customer_district = '".$data['customer_district']."',
        customer_province = '".$data['customer_province']."',
        customer_zipcode = '".$data['customer_zipcode']."', 
        customer_username = '".$data['customer_username']."', 
        customer_password = '".$data['customer_password']."', 
        lastupdate = NOW() 
        WHERE customer_code = $code ";
        
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
    
    function updateCustomerByCode($code,$data = []){
        $data['customer_name']=mysqli_real_escape_string(static::$db,$data['customer_name']);
        $data['customer_image']=mysqli_real_escape_string(static::$db,$data['customer_image']);
        $data['customer_phone']=mysqli_real_escape_string(static::$db,$data['customer_phone']);
        $data['customer_email']=mysqli_real_escape_string(static::$db,$data['customer_email']);
        $data['customer_address']=mysqli_real_escape_string(static::$db,$data['customer_address']);
        $data['customer_province']=mysqli_real_escape_string(static::$db,$data['customer_province']);
        $data['customer_amphur']=mysqli_real_escape_string(static::$db,$data['customer_amphur']);
        $data['customer_district']=mysqli_real_escape_string(static::$db,$data['customer_district']);
        $data['customer_zipcode']=mysqli_real_escape_string(static::$db,$data['customer_zipcode']);
        $data['customer_username']=mysqli_real_escape_string(static::$db,$data['customer_username']);
        $data['customer_password']=mysqli_real_escape_string(static::$db,$data['customer_password']);

        $sql = "UPDATE tb_customer SET 
        customer_name = '".$data['customer_name']."', 
        customer_image = '".$data['customer_image']."', 
        customer_phone = '".$data['customer_phone']."', 
        customer_email = '".$data['customer_email']."', 
        customer_address = '".$data['customer_address']."',
        customer_province = '".$data['customer_province']."',
        customer_amphur = '".$data['customer_amphur']."',
        customer_district = '".$data['customer_district']."',
        customer_zipcode = '".$data['customer_zipcode']."', 
        customer_username = '".$data['customer_username']."', 
        customer_password = '".$data['customer_password']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE customer_code = $code ";
        
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function insertCustomer($data=[]){
        $sql = " INSERT INTO tb_customer(
            customer_code,
            customer_firstname,
            customer_lastname,
            customer_image,
            customer_phone,
            customer_email,
            customer_tax,
            customer_address,
            customer_province,
            customer_amphur,
            customer_district,
            customer_zipcode,
            customer_username,
            customer_password,
            addby,
            adddate
        ) VALUES ('".
        mysqli_real_escape_string(static::$db,$data['customer_code'])."','".
        mysqli_real_escape_string(static::$db,$data['customer_firstname'])."','".
        mysqli_real_escape_string(static::$db,$data['customer_lastname'])."','".
        mysqli_real_escape_string(static::$db,$data['customer_image'])."','".
        mysqli_real_escape_string(static::$db,$data['customer_phone'])."','".
        mysqli_real_escape_string(static::$db,$data['customer_email'])."','".
        mysqli_real_escape_string(static::$db,$data['customer_tax'])."','".
        mysqli_real_escape_string(static::$db,$data['customer_address'])."','".
        mysqli_real_escape_string(static::$db,$data['customer_province'])."','".
        mysqli_real_escape_string(static::$db,$data['customer_amphur'])."','".
        mysqli_real_escape_string(static::$db,$data['customer_district'])."','".
        mysqli_real_escape_string(static::$db,$data['customer_zipcode'])."','".
        mysqli_real_escape_string(static::$db,$data['customer_username'])."','".
        mysqli_real_escape_string(static::$db,$data['customer_password'])."','".
        $data['addby'].
        "',NOW())";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return false;
        }
    }

    function deleteCustomerByCode($code){
        $sql = " DELETE FROM tb_customer WHERE customer_code = '$code' ";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>