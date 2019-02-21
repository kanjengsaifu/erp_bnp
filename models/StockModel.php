<?php
require_once("BaseModel.php");
require_once("StockReportModel.php"); 

class StockModel extends BaseModel{

    private $stock_report;

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
        $this->stock_report =  new StockReportModel;
    }

    
    function getStockLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(stock_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_stock 
        WHERE stock_code LIKE ('$code%') 
        ";
        // echo $sql.'<br><br>';
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    }

    //##########################################################################################################
    //
    //####################################### ตรวจสอบและสร้างรายการรายงานคลังสินค้า #################################
    //
    //##########################################################################################################

    function createRowStockReport($data = []){
        $sql = "SELECT COUNT(*) as check_row 
        FROM tb_stock_report 
        WHERE tb_stock_report.material_code = '".$data['material_code']."' 
        AND tb_stock_report.branch_code = '".$data['branch_code']."'
        ;";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock_report = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            
            if($stock_report['check_row'] == 0){
                
                $code = $this->stock_report->getStockReportLastCode("STKR",4); 
                $sql = "INSERT INTO tb_stock_report ( 
                    stock_report_code,
                    material_code, 
                    branch_code, 
                    addby, 
                    adddate, 
                    updateby, 
                    lastupdate 
                ) VALUES (
                '".$code."', 
                '".$data['material_code']."', 
                '".$data['branch_code']."',  
                '".$data['addby']."', 
                NOW(),  
                '".$data['addby']."',  
                NOW() 
                ); "; 

                mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            }
        } 
    }

    //##########################################################################################################
    //
    //############################# คำนวนจำนวน และ ต้นทุนสินค้าใหม่เมือเพิ่มรายรับสินค้าเข้า ###############################
    //
    //##########################################################################################################

    function calculatePurchaseCostIn($data = []){
        $sql = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE material_code = '".$data['material_code']."' 
        AND tb_stock_report.branch_code = '".$data['branch_code']."' ;"; 

       

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock_report = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();
            
            $stock_qty = $stock_report['stock_report_qty']; 

            $new_qty = $stock_qty + $data['invoice_supplier_list_qty']; 
            
 
            $sql = "UPDATE tb_stock_report 
                    SET stock_report_qty = '$new_qty',  
                    updateby = '".$data['updateby']."',
                    lastupdate = NOW() 
                    WHERE material_code = '".$data['material_code']."' 
                    AND tb_stock_report.branch_code = '".$data['branch_code']."' ; "; 

            //echo "<br><br>SQL calculatePurchaseCostIn : ".$sql;

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

            $stock_report['stock_report_qty'] = $new_qty; 

            return $stock_report;
        }
    }

    
    //##########################################################################################################
    //
    //############################# คำนวนจำนวน และ ต้นทุนสินค้าใหม่เมือลบรายรับสินค้าเข้า ###############################
    //
    //##########################################################################################################

    function calculatePurchaseCostOut($data=[]){
        $sql = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE material_code = '".$data['material_code']."' 
        AND tb_stock_report.branch_code = '".$data['branch_code']."' ;";  

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock_report = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();
            
            $stock_qty = $stock_report['stock_report_qty']; 

            $new_qty = $stock_qty - $data['invoice_supplier_list_qty']; 
            
 
            $sql = "UPDATE tb_stock_report SET 
                    stock_report_qty = '$new_qty',  
                    updateby = '".$data['updateby']."',
                    lastupdate = NOW()   
                    WHERE material_code = '".$data['material_code']."' 
                    AND tb_stock_report.branch_code = '".$data['branch_code']."' ; "; 

            //echo "<br><br>SQL calculatePurchaseCostIn : ".$sql;

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

            $stock_report['stock_report_qty'] = $new_qty; 

            return $stock_report;
        }
    }
    
    //##########################################################################################################
    //
    //############################# คำนวนจำนวน และ เมื่อลบการเบิกวัตถุดิบ ###############################
    //
    //##########################################################################################################

    function calculateWithdrawCostIn($data = []){
        $sql = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE material_code = '".$data['material_code']."' 
        AND tb_stock_report.branch_code = '".$data['branch_code']."' ;"; 

       

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock_report = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();
            
            $stock_qty = $stock_report['stock_report_qty']; 

            $new_qty = $stock_qty + $data['withdraw_list_qty']; 
            
 
            $sql = "UPDATE tb_stock_report 
                    SET stock_report_qty = '$new_qty',  
                    updateby = '".$data['updateby']."',
                    lastupdate = NOW()     
                    WHERE material_code = '".$data['material_code']."' 
                    AND tb_stock_report.branch_code = '".$data['branch_code']."' ; "; 

            //echo "<br><br>SQL calculatePurchaseCostIn : ".$sql;

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

            $stock_report['stock_report_qty'] = $new_qty; 

            return $stock_report;
        }
    }

    //##########################################################################################################
    //
    //############################# update tb_stock_report เมื่อมีการเบิกวัตถุดิบ ###############################
    //
    //##########################################################################################################

    function calculateWithdrawCostOut($data=[]){
        $sql = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE material_code = '".$data['material_code']."' 
        AND tb_stock_report.branch_code = '".$data['branch_code']."' ;";  

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock_report = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();
            
            $stock_qty = $stock_report['stock_report_qty']; 

            $new_qty = $stock_qty - $data['withdraw_list_qty']; 
            
 
            $sql = "UPDATE tb_stock_report SET 
                    stock_report_qty = '$new_qty',  
                    updateby = '".$data['updateby']."',
                    lastupdate = NOW()   
                    WHERE material_code = '".$data['material_code']."' 
                    AND tb_stock_report.branch_code = '".$data['branch_code']."' ; "; 

            //echo "<br><br>SQL calculatePurchaseCostIn : ".$sql;

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

            $stock_report['stock_report_qty'] = $new_qty; 

            return $stock_report;
        }
    }

    function removePurchase($data=[]){
        // $stock = $this->getStockGroupTable($stock_group_id); 

        $this->createRowStockReport($data);
        $stock_report = $this->calculatePurchaseCostOut($data);

        $sql = "DELETE FROM tb_stock WHERE invoice_supplier_list_code ='".$data['invoice_supplier_list_code']."';";
        //  echo $sql.'<br><br>';
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);  

    }   

    function addPurchase($data = []){  
        $this->createRowStockReport($data);
        $stock_report = $this->calculatePurchaseCostIn($data);
        $code = $this->getStockLastCode("STK",7);
        $sql = "INSERT INTO tb_stock(
            stock_code,  
            material_code, 
            invoice_supplier_list_code, 
            withdraw_list_code, 
            stock_date, 
            stock_type, 
            stock_in_qty, 
            stock_in_price, 
            stock_in_price_sum, 
            stock_in_cost_avg, 
            stock_in_cost_avg_total, 
            stock_out_qty, 
            stock_out_price, 
            stock_out_price_sum, 
            stock_out_cost_avg, 
            stock_out_cost_avg_total, 
            stock_balance_qty, 
            stock_balance_price, 
            stock_balance_price_sum, 
            stock_balance_cost_avg, 
            stock_balance_cost_avg_total, 
            branch_code, 
            adddate 
            ) VALUES ( 
            '".$code."',  
            '".$data['material_code']."', 
            '".$data['invoice_supplier_list_code']."', 
            '".$data['withdraw_list_code']."', 
            '".$data['invoice_supplier_date_recieve']."', 
            'in', 
            '".$data['invoice_supplier_list_qty']."', 
            '".$data['invoice_supplier_list_price']."', 
            '".$data['invoice_supplier_list_price_sum']."', 
            '".$data['stock_in_cost_avg']."', 
            '".$data['stock_in_cost_avg_total']."', 
            '".$data['stock_out_qty']."', 
            '".$data['stock_out_price']."', 
            '".$data['stock_out_price_sum']."', 
            '".$data['stock_out_cost_avg']."', 
            '".$data['stock_out_cost_avg_total']."', 
            '".$data['stock_balance_qty']."', 
            '".$data['stock_balance_price']."', 
            '".$data['stock_balance_price_sum']."', 
            '".$data['stock_balance_cost_avg']."', 
            '".$data['stock_balance_cost_avg_total']."', 
            '".$data['branch_code']."', 
            NOW() 
            )";  
            // echo $sql.'<br><br>'; 
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
 
    }    

    
    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการขายสินค้า #################################
    //
    //##########################################################################################################

    function addWithdraw($data = []){ 
        $this->createRowStockReport($data);
        $stock_report = $this->calculateWithdrawCostOut($data);
        $code = $this->getStockLastCode("STK",7);
        $sql = "INSERT INTO tb_stock(
            stock_code,  
            material_code,  
            withdraw_list_code,  
            stock_type,  
            stock_out_qty,
            branch_code, 
            adddate  
            ) VALUES ( 
            '".$code."',  
            '".$data['material_code']."',  
            '".$data['withdraw_list_code']."',  
            'out',  
            '".$data['withdraw_list_qty']."',  
            '".$data['branch_code']."',  
            NOW() 
            )";  
            // echo $sql.'<br><br>'; 
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    } 

 






    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อลบรายการขายสินค้า #################################
    //
    //##########################################################################################################

    function removeWithdraw($data=[]){
     
        $this->createRowStockReport($data);
        $stock_report = $this->calculateWithdrawCostIn($data);

        $sql = "DELETE FROM tb_stock WHERE withdraw_list_code ='".$data['withdraw_list_code']."';";
        //  echo $sql.'<br><br>';
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);  

    }

}
?>