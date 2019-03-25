<?php
require_once("BaseModel.php");

class StockMoveModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getStockMoveBy($date_start  = '', $date_end  = '',$keyword = ""){

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(stock_move_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(stock_move_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(stock_move_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(stock_move_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        $sql = " SELECT stock_move_code, 
        stock_group_code_out, 
        stock_group_code_in, 
        stock_move_code, 
        stock_move_date, 
        tb1.stock_group_name as move_group_name_out,
        tb2.stock_group_name as move_group_name_in,
        stock_move_remark, 
        IFNULL(CONCAT(user_name,' ',user_lastname),'-') as employee_name 
        FROM tb_stock_move 
        LEFT JOIN tb_user ON tb_stock_move.employee_code = tb_user.user_code 
        LEFT JOIN tb_stock_group as tb1 ON tb_stock_move.stock_group_code_out = tb1.stock_group_code 
        LEFT JOIN tb_stock_group as tb2 ON tb_stock_move.stock_group_code_in = tb2.stock_group_code 
        WHERE stock_move_code LIKE ('%$keyword%') 
        $str_date
        ORDER BY stock_move_code DESC 
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

    function getStockMoveByCode($id){
        $sql = " SELECT stock_move_code, 
        tb_stock_move.employee_code, 
        stock_group_code_out, 
        stock_group_code_in, 
        stock_move_code, 
        stock_move_date, 
        tb1.stock_group_name as move_group_name_out,
        tb2.stock_group_name as move_group_name_in,
        stock_move_remark, 
        IFNULL(CONCAT(user_name,' ',user_lastname),'-') as employee_name 
        FROM tb_stock_move 
        LEFT JOIN tb_user ON tb_stock_move.employee_code = tb_user.user_code 
        LEFT JOIN tb_stock_group as tb1 ON tb_stock_move.stock_group_code_out = tb1.stock_group_code 
        LEFT JOIN tb_stock_group as tb2 ON tb_stock_move.stock_group_code_in = tb2.stock_group_code 
        WHERE stock_move_code = '$id' 
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

    function getStockMoveViewByCode($id){
        $sql = " SELECT stock_move_code, 
        tb_stock_move.employee_code, 
        stock_group_code_out, 
        stock_group_code_in, 
        stock_move_code, 
        stock_move_date, 
        tb1.stock_group_name as move_group_name_out,
        tb2.stock_group_name as move_group_name_in,
        stock_move_remark, 
        IFNULL(CONCAT(user_name,' ',user_lastname),'-') as employee_name 
        FROM tb_stock_move 
        LEFT JOIN tb_user ON tb_stock_move.employee_code = tb_user.user_code 
        LEFT JOIN tb_stock_group as tb1 ON tb_stock_move.stock_group_code_out = tb1.stock_group_code 
        LEFT JOIN tb_stock_group as tb2 ON tb_stock_move.stock_group_code_in = tb2.stock_group_code 
        WHERE stock_move_code = '$id' 
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

    function getStockMoveLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(RIGHT(stock_move_code,3) AS SIGNED)),0) + 1,$digit,'0' )) AS  stock_move_lastcode 
        FROM tb_stock_move 
        WHERE stock_move_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['stock_move_lastcode'];
        }

    }

   
    function updateStockMoveByCode($id,$data = []){
        $sql = " UPDATE tb_stock_move SET 
        stock_group_code_out = '".$data['stock_group_code_out']."', 
        stock_group_code_in = '".$data['stock_group_code_in']."', 
        employee_code = '".$data['employee_code']."', 
        stock_move_code = '".$data['stock_move_code']."', 
        stock_move_date = '".$data['stock_move_date']."', 
        stock_move_remark = '".$data['stock_move_remark']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE stock_move_code = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertStockMove($data = []){
        $sql = " INSERT INTO tb_stock_move (
            stock_group_code_out, 
            stock_group_code_in, 
            employee_code,
            stock_move_code,
            stock_move_date,
            stock_move_remark,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['stock_group_code_out']."','".
        $data['stock_group_code_in']."','".
        $data['employee_code']."','".
        $data['stock_move_code']."','".
        $data['stock_move_date']."','".
        $data['stock_move_remark']."','".
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_code(static::$db);
        }else {
            return 0;
        }

    }



    function deleteStockMoveByCode($id){
 
        $sql = " DELETE FROM tb_stock_move_list WHERE stock_move_code = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_stock_move WHERE stock_move_code = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


}
?>