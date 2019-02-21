<?php
require_once("BaseModel.php");

class StockReportModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    
    function getStockReportLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(stock_report_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_stock_report 
        WHERE stock_report_code LIKE ('$code%') 
        ";
        // echo $sql.'<br><br>';
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    
    function generateStockReportBy($branch_code, $data = [], $search = "" ){
        // $data = [];
        $str ="'0'";

        if(is_array($data)){  
            for($i=0; $i < count($data) ;$i++){
                if($i==0){
                    $str ="";
                }
                $str .= "'".$data[$i]."'";
                if($i + 1 < count($data)){
                    $str .= ',';
                }
            }
        }else if ($data != ''){
            $str = "'".$data."'";
        }else{
            $str="'0'";
        } 

        $sql = "SELECT * 
        FROM tb_stock_report  
        LEFT JOIN tb_material ON tb_stock_report.material_code = tb_material.material_code  
        LEFT JOIN tb_material_unit ON tb_material.material_unit_code = tb_material_unit.material_unit_code 
        WHERE 1 
        AND tb_stock_report.branch_code = '$branch_code' 
        AND tb_stock_report.stock_report_code NOT IN ($str) 
        AND tb_stock_report.stock_report_code IN ( 
            SELECT tb_stock_report.stock_report_code 
            FROM tb_stock_report  
            WHERE stock_report_qty > 0   
        ) 
        AND CONCAT(tb_stock_report.material_code,material_name) LIKE ('%$search%')  
        ORDER BY tb_stock_report.material_code   ";  
 
        // echo $sql;

        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        } 

        return $data;
    }

}
?>