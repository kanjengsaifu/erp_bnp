<?php
require_once("BaseModel.php");

class DealerModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getDealerLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(dealer_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS lastcode 
        FROM tb_dealer 
        WHERE dealer_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function getDealerBy($name = '', $mobile  = ''){
        $sql = "SELECT dealer_code, dealer_prefix, CONCAT(dealer_name,' ',dealer_lastname) as name,
        dealer_mobile, dealer_line, dealer_fund_name, dealer_fund_budget, PROVINCE_NAME, AMPHUR_NAME, DISTRICT_NAME, VILLAGE_NAME
        FROM tb_dealer 
        LEFT JOIN tb_village ON tb_dealer.village_id = tb_village.VILLAGE_ID 
        LEFT JOIN tb_district ON tb_village.DISTRICT_ID = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
        WHERE CONCAT(tb_dealer.dealer_name,' ',tb_dealer.dealer_lastname) LIKE ('%$name%') 
        AND dealer_mobile LIKE ('%$mobile%') 
        ORDER BY CONCAT(tb_dealer.dealer_name,' ',tb_dealer.dealer_lastname) 
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
    
    function getDealerByUserCode($user_code){
        $sql = "SELECT tb_dealer.dealer_code AS code,  CONCAT(dealer_prefix,' ',dealer_name,' ',dealer_lastname) as name 
        FROM tb_dealer  
        INNER JOIN tb_zone_list_dealer ON tb_dealer.dealer_code = tb_zone_list_dealer.dealer_code 
        INNER JOIN tb_zone_list ON tb_zone_list_dealer.zone_list_code = tb_zone_list.zone_list_code 
        INNER JOIN tb_zone_call_center ON tb_zone_list.zone_code = tb_zone_call_center.zone_code 
        WHERE tb_zone_call_center.user_code = '$user_code' 
        ORDER BY CONCAT(tb_dealer.dealer_name,' ',tb_dealer.dealer_lastname) 
        ";
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getDealerByCode($code){
        $sql = " SELECT * 
        FROM tb_dealer 
        LEFT JOIN tb_status ON tb_dealer.status_code = tb_status.status_code 
        LEFT JOIN tb_village ON tb_dealer.village_id = tb_village.VILLAGE_ID 
        LEFT JOIN tb_district ON tb_village.DISTRICT_ID = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
        WHERE dealer_code = '$code' 
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

    function getDealerByStatus($code){
        $sql = " SELECT dealer_code, dealer_prefix, CONCAT(dealer_name,' ',dealer_lastname) as name,
        dealer_mobile, dealer_line, dealer_fund_name, dealer_fund_budget, PROVINCE_NAME, AMPHUR_NAME, DISTRICT_NAME, VILLAGE_NAME
        FROM tb_dealer 
        LEFT JOIN tb_village ON tb_dealer.village_id = tb_village.VILLAGE_ID 
        LEFT JOIN tb_district ON tb_village.DISTRICT_ID = tb_district.DISTRICT_ID 
        LEFT JOIN tb_amphur ON tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID 
        LEFT JOIN tb_province ON tb_district.PROVINCE_ID = tb_province.PROVINCE_ID 
        WHERE status_code = '$code' 
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

    function getDealerByUsername($code,$user){
        $sql = "SELECT * 
        FROM tb_dealer 
        WHERE dealer_code != '$code' AND dealer_username = '$user' 
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

    function countDealerByStatus($code){
        $sql = " SELECT COUNT(dealer_code) AS total
        FROM tb_dealer 
        WHERE status_code = '$code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['total'];
        }
    }

    function getDealerByDistrict($id){
        $sql = "SELECT dealer_code, dealer_prefix, CONCAT(dealer_name,' ',dealer_lastname) as name
        FROM tb_dealer 
        WHERE district_id = '$id' 
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

    function approveDealerByCode($code){
        $sql = " UPDATE tb_dealer SET 
        status_code = '01' 
        WHERE dealer_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateDealerByCode($code,$data = []){
        $sql = " UPDATE tb_dealer SET 
        status_code = '".static::$db->real_escape_string($data['status_code'])."',
        dealer_prefix = '".static::$db->real_escape_string($data['dealer_prefix'])."', 
        dealer_name = '".static::$db->real_escape_string($data['dealer_name'])."', 
        dealer_lastname = '".static::$db->real_escape_string($data['dealer_lastname'])."', 
        dealer_address = '".static::$db->real_escape_string($data['dealer_address'])."', 
        dealer_fund_name = '".static::$db->real_escape_string($data['dealer_fund_name'])."', 
        dealer_fund_budget = '".static::$db->real_escape_string($data['dealer_fund_budget'])."', 
        village_id = '".static::$db->real_escape_string($data['village_id'])."', 
        dealer_mobile = '".static::$db->real_escape_string($data['dealer_mobile'])."',  
        dealer_line = '".static::$db->real_escape_string($data['dealer_line'])."',  
        profile_image = '".static::$db->real_escape_string($data['profile_image'])."', 
        id_card_image = '".static::$db->real_escape_string($data['id_card_image'])."', 
        dealer_username = '".static::$db->real_escape_string($data['dealer_username'])."', 
        dealer_password = '".static::$db->real_escape_string($data['dealer_password'])."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE dealer_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertDealer($data = []){
        $data['dealer_code']=mysqli_real_escape_string(static::$db,$data['dealer_code']);
        $data['dealer_name']=mysqli_real_escape_string(static::$db,$data['dealer_name']);
        $data['dealer_lastname']=mysqli_real_escape_string(static::$db,$data['dealer_lastname']);
        $data['dealer_address']=mysqli_real_escape_string(static::$db,$data['dealer_address']);
        $data['dealer_fund_name']=mysqli_real_escape_string(static::$db,$data['dealer_fund_name']);
        $data['dealer_fund_budget']=mysqli_real_escape_string(static::$db,$data['dealer_fund_budget']);
        $data['dealer_mobile']=mysqli_real_escape_string(static::$db,$data['dealer_mobile']);
        $data['dealer_line']=mysqli_real_escape_string(static::$db,$data['dealer_line']);
        $data['profile_image']=mysqli_real_escape_string(static::$db,$data['profile_image']);
        $data['id_card_image']=mysqli_real_escape_string(static::$db,$data['id_card_image']);
        $data['dealer_username']=mysqli_real_escape_string(static::$db,$data['dealer_username']);
        $data['dealer_password']=mysqli_real_escape_string(static::$db,$data['dealer_password']);

        $sql = " INSERT INTO tb_dealer ( 
            dealer_code,
            status_code,
            dealer_prefix,
            dealer_name, 
            dealer_lastname,
            dealer_address,
            dealer_fund_name,
            dealer_fund_budget,
            village_id,
            dealer_mobile,
            dealer_line,
            profile_image,
            id_card_image,
            dealer_username,
            dealer_password,
            addby,
            adddate
            )  VALUES ('".
            $data['dealer_code']."','".
            $data['status_code']."','".
            $data['dealer_prefix']."','".
            $data['dealer_name']."','".
            $data['dealer_lastname']."','".
            $data['dealer_address']."','".
            $data['dealer_fund_name']."','".
            $data['dealer_fund_budget']."','".
            $data['village_id']."','".
            $data['dealer_mobile']."','".
            $data['dealer_line']."','".
            $data['profile_image']."','".
            $data['id_card_image']."','".
            $data['dealer_username']."','".
            $data['dealer_password']."','".
            $data['addby']."',
            NOW()
        )";

        // echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteDealerByCode($code){
        $sql = " DELETE FROM tb_dealer WHERE dealer_code = '$code' ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>