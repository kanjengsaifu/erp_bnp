<?php
require_once("BaseModel.php");

class DashboardModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getDashboardBy($date_start = "",$date_end = "",$keyword = "",$user_code = ""){
        if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(dashboard_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(dashboard_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_code != ""){
            $str_user = "AND employee_code = '$user_code' ";
        }

        $sql = " SELECT *
        FROM tb_dashboard
        WHERE dashboard_code LIKE ('%$keyword%') 
        $str_date 
        $str_user  
        GROUP BY dashboard_code
        ORDER BY dashboard_code DESC 
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

    function getDashboardByKeyword($keyword = ""){
        $sql = " SELECT *
        FROM tb_dashboard
        WHERE dashboard_code LIKE ('%$keyword%')   
        ORDER BY dashboard_code DESC 
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

    function getDashboardByCode($code){
        $sql = " SELECT * 
        FROM tb_dashboard 
        WHERE dashboard_code = '$code' 
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

    function getDashboardLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(dashboard_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS lastcode 
        FROM tb_dashboard 
        WHERE dashboard_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    function cancelDashboardByCode($code){
        $sql = " UPDATE tb_dashboard SET 
        dashboard_cancelled = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE dashboard_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function uncancelDashboardByCode($code){
        $sql = " UPDATE tb_dashboard SET 
        dashboard_cancelled = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE dashboard_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateDashboardByCode($code,$data = []){
        $sql = " UPDATE tb_dashboard SET  
        supplier_code = '".$data['supplier_code']."', 
        employee_code = '".$data['employee_code']."', 
        dashboard_code = '".$data['dashboard_code']."', 
        dashboard_credit_term = '".$data['dashboard_credit_term']."', 
        dashboard_delivery_term = '".$data['dashboard_delivery_term']."', 
        dashboard_delivery_by = '".$data['dashboard_delivery_by']."', 
        dashboard_date = '".$data['dashboard_date']."', 
        dashboard_status = '".$data['dashboard_status']."', 
        dashboard_total_price = '".$data['dashboard_total_price']."', 
        dashboard_vat = '".$data['dashboard_vat']."', 
        dashboard_vat_price = '".$data['dashboard_vat_price']."', 
        dashboard_net_price = '".$data['dashboard_net_price']."',
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE dashboard_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateDashboardAcceptByCode($code,$data = []){
        $sql = " UPDATE tb_dashboard SET 
        dashboard_accept_status = '".$data['dashboard_accept_status']."', 
        dashboard_accept_by = '".$data['dashboard_accept_by']."', 
        dashboard_accept_date = NOW(), 
        dashboard_status = '".$data['dashboard_status']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE dashboard_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateDashboardRequestByCode($code,$data = []){
        $sql = " UPDATE tb_dashboard SET 
        dashboard_accept_status = '".$data['dashboard_accept_status']."', 
        dashboard_accept_by = '".$data['dashboard_accept_by']."', 
        dashboard_accept_date = '', 
        dashboard_status = '".$data['dashboard_status']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE dashboard_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertDashboard($data = []){
        $sql = " INSERT INTO tb_dashboard ( 
            supplier_code,
            employee_code,
            dashboard_rewrite_code,
            dashboard_rewrite_no,
            dashboard_accept_status,
            dashboard_accept_by,
            dashboard_accept_date,
            dashboard_status,
            dashboard_type,
            dashboard_code,
            dashboard_credit_term,
            dashboard_delivery_term,
            dashboard_delivery_by,
            dashboard_date,
            dashboard_total_price,
            dashboard_vat,
            dashboard_vat_price,
            dashboard_net_price,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('". 
        $data['supplier_code']."','".
        $data['employee_code']."','".
        $data['dashboard_rewrite_code']."','".
        $data['dashboard_rewrite_no']."','".
        $data['dashboard_accept_status']."','".
        $data['dashboard_accept_by']."','".
        $data['dashboard_accept_date']."','".
        $data['dashboard_status']."','".
        $data['dashboard_type']."','".
        $data['dashboard_code']."','".
        $data['dashboard_credit_term']."','".
        $data['dashboard_delivery_term']."','".
        $data['dashboard_delivery_by']."','".
        $data['dashboard_date']."','".
        $data['dashboard_total_price']."','".
        $data['dashboard_vat']."','".
        $data['dashboard_vat_price']."','".
        $data['dashboard_net_price']."','".
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_code(static::$db);
        }else {
            return '';
        }
    }

    function deleteDashboardByCode($code){

        $sql = " UPDATE tb_purchase_request_list SET dashboard_list_code = '0' WHERE dashboard_list_code (SELECT dashboard_list_code FROM tb_dashboard_list WHERE dashboard_code = '$code') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " UPDATE tb_customer_dashboard_list_detail SET dashboard_list_code = '0' WHERE dashboard_list_code (SELECT dashboard_list_code FROM tb_dashboard_list WHERE dashboard_code = '$code') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " UPDATE tb_delivery_note_supplier_list SET dashboard_list_code = '0' WHERE dashboard_list_code (SELECT dashboard_list_code FROM tb_dashboard_list WHERE dashboard_code = '$code') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " UPDATE tb_regrind_supplier_receive_list SET dashboard_list_code = '0' WHERE dashboard_list_code (SELECT dashboard_list_code FROM tb_dashboard_list WHERE dashboard_code = '$code') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " UPDATE tb_request_standard_list SET dashboard_list_code = '0' WHERE dashboard_list_code (SELECT dashboard_list_code FROM tb_dashboard_list WHERE dashboard_code = '$code') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " UPDATE tb_request_special_list SET dashboard_list_code = '0' WHERE dashboard_list_code (SELECT dashboard_list_code FROM tb_dashboard_list WHERE dashboard_code = '$code') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " UPDATE tb_request_regrind_list SET dashboard_list_code = '0' WHERE dashboard_list_code (SELECT dashboard_list_code FROM tb_dashboard_list WHERE dashboard_code = '$code') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_dashboard WHERE dashboard_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        
        $sql = " DELETE FROM tb_dashboard_list WHERE dashboard_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
}
?>