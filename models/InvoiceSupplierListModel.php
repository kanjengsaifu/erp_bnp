<?php
require_once("BaseModel.php");
require_once("StockModel.php"); 

class InvoiceSupplierListModel extends BaseModel{

    private $stock;

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
        $this->stock =  new StockModel;
    }

    function getInvoiceSupplierListLastCode($code,$digit){
        $sql = "SELECT CONCAT('$code' , LPAD(IFNULL(MAX(CAST(SUBSTRING(invoice_supplier_list_code,".(strlen($code)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  lastcode 
        FROM tb_invoice_supplier_list 
        WHERE invoice_supplier_list_code LIKE ('$code%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['lastcode'];
        }
    } 

    function getInvoiceSupplierListBy($invoice_supplier_code){
        $sql = " SELECT tb_invoice_supplier_list.material_code, 
        invoice_supplier_list_code, 
        material_name,   
        tb_invoice_supplier_list.purchase_order_list_code,
        purchase_order_list_price,
        purchase_order_list_price_sum, 
        invoice_supplier_list_material_name, 
        invoice_supplier_list_material_detail, 
        invoice_supplier_list_qty,   
        invoice_supplier_list_price, 
        invoice_supplier_list_price_sum,  
        invoice_supplier_list_remark , 
        CONCAT('PO : ',tb_purchase_order.purchase_order_code) as purchase_order_code
        FROM tb_invoice_supplier_list 
        LEFT JOIN tb_material ON tb_invoice_supplier_list.material_code = tb_material.material_code  
        LEFT JOIN tb_purchase_order_list ON tb_invoice_supplier_list.purchase_order_list_code = tb_purchase_order_list.purchase_order_list_code 
        LEFT JOIN tb_purchase_order ON tb_purchase_order_list.purchase_order_code = tb_purchase_order.purchase_order_code 
        WHERE invoice_supplier_code = '$invoice_supplier_code' 
        ORDER BY invoice_supplier_list_no ,invoice_supplier_list_code 
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

    
    function getInvoiceSupplierListByCode($code){
        $sql = " SELECT * 
        FROM tb_invoice_supplier_list 
        WHERE invoice_supplier_list_code = '$code'  
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

    function updateInvoiceSupplierListById($data,$code){

        $data_old = $this->getInvoiceSupplierListByCode($code);
        $data_old['branch_code'] = $data['branch_code'];

        $sql = " UPDATE tb_invoice_supplier_list 
            SET material_code = '".$data['material_code']."', 
            invoice_supplier_list_no = '".$data['invoice_supplier_list_no']."',  
            invoice_supplier_list_material_name = '".$data['invoice_supplier_list_material_name']."',  
            invoice_supplier_list_material_detail = '".$data['invoice_supplier_list_material_detail']."', 
            invoice_supplier_list_qty = '".$data['invoice_supplier_list_qty']."',  
            invoice_supplier_list_price = '".$data['invoice_supplier_list_price']."', 
            invoice_supplier_list_price_sum = '".$data['invoice_supplier_list_price_sum']."', 
            invoice_supplier_list_remark = '".$data['invoice_supplier_list_remark']."',   
            purchase_order_list_code = '".$data['purchase_order_list_code']."', 
            updateby = '".$data['updateby']."', 
            lastupdate = NOW()  
            WHERE invoice_supplier_list_code = '$code' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) { 
            $this->stock->removePurchase($data_old);
            $this->stock->addPurchase($data);
 
           return true;
        }else {
            return false;
        }
    }

    function insertInvoiceSupplierList($data = []){
        $sql = " INSERT INTO tb_invoice_supplier_list ( 
            invoice_supplier_list_code,
            invoice_supplier_code,
            invoice_supplier_list_no,
            material_code,
            invoice_supplier_list_material_name,
            invoice_supplier_list_material_detail, 
            invoice_supplier_list_qty,
            invoice_supplier_list_price, 
            invoice_supplier_list_price_sum,
            invoice_supplier_list_remark, 
            purchase_order_list_code, 
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES ( 
            '".$data['invoice_supplier_list_code']."', 
            '".$data['invoice_supplier_code']."', 
            '".$data['invoice_supplier_list_no']."', 
            '".$data['material_code']."', 
            '".$data['invoice_supplier_list_material_name']."', 
            '".$data['invoice_supplier_list_material_detail']."',  
            '".$data['invoice_supplier_list_qty']."', 
            '".$data['invoice_supplier_list_price']."', 
            '".$data['invoice_supplier_list_price_sum']."', 
            '".$data['invoice_supplier_list_remark']."', 
            '".$data['purchase_order_list_code']."',  
            '".$data['addby']."', 
            NOW(), 
            '".$data['addby']."', 
            NOW() 
        ); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {  
             
            $this->stock->addPurchase($data);
            return $data['invoice_supplier_list_code']; 

        }else {
            return 0;
        }

    }

    function deleteInvoiceSupplierListByInvoiceSupplierCodeNotIN($code,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
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
 
        $sql = "    SELECT tb_invoice_supplier_list.invoice_supplier_list_code,  tb_invoice_supplier_list.material_code,  tb_invoice_supplier_list.invoice_supplier_list_qty ,tb_invoice_supplier.branch_code 
                    FROM  tb_invoice_supplier_list 
                    LEFT JOIN tb_invoice_supplier ON tb_invoice_supplier_list.invoice_supplier_code = tb_invoice_supplier.invoice_supplier_code 
                    WHERE tb_invoice_supplier_list.invoice_supplier_code = '$code' 
                    AND invoice_supplier_list_code NOT IN ($str) ";   
        
        $data_clear=[];

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
            
                $data_clear [] = $row;
            
            }
            $result->close();
        }

        for($i = 0 ; $i < count($data_clear); $i++){
            $this->stock->removePurchase($data_clear[$i]); 
        } 

        $sql = "DELETE FROM tb_invoice_supplier_list WHERE invoice_supplier_code = '$code' AND invoice_supplier_list_code NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
}
?>