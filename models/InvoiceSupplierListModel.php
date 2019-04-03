<?php
require_once("BaseModel.php");

class InvoiceSupplierListModel extends BaseModel{


    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getInvoiceSupplierListBy($invoice_supplier_code){
        $sql = " SELECT invoice_supplier_list_code,
        tb1.product_code,
        product_name, 
        tb1.stock_group_code,
        stock_group_name, 
        tb1.purchase_order_list_code,
        purchase_order_list_price,
        purchase_order_list_price_sum,
        invoice_supplier_list_product_name, 
        invoice_supplier_list_product_detail, 
        invoice_supplier_list_qty, 
        invoice_supplier_list_price, 
        invoice_supplier_list_total,
        invoice_supplier_list_freight, 
        invoice_supplier_list_freight_total, 
        invoice_supplier_list_cost, 
        invoice_supplier_list_cost_total, 
        invoice_supplier_list_fix_type, 
        invoice_supplier_list_remark 
        FROM tb_invoice_supplier_list AS tb1
        LEFT JOIN tb_product ON tb1.product_code = tb_product.product_code 
        LEFT JOIN tb_stock_group ON tb1.stock_group_code = tb_stock_group.stock_group_code 
        LEFT JOIN tb_purchase_order_list ON tb1.purchase_order_list_code = tb_purchase_order_list.purchase_order_list_code 
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
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }

    function insertInvoiceSupplierList($data = []){
        $sql = " INSERT INTO tb_invoice_supplier_list ( 
            invoice_supplier_list_code,
            invoice_supplier_code,
            product_code,
            stock_group_code,
            invoice_supplier_list_product_name,
            invoice_supplier_list_product_detail,
            invoice_supplier_list_no,
            invoice_supplier_list_qty,
            invoice_supplier_list_price, 
            invoice_supplier_list_total,
            invoice_supplier_list_freight, 
            invoice_supplier_list_freight_total, 
            invoice_supplier_list_cost, 
            invoice_supplier_list_cost_total, 
            invoice_supplier_list_remark,
            purchase_order_list_code,
            addby,
            adddate
        ) VALUES ( 
            '".$data['invoice_supplier_list_code']."', 
            '".$data['invoice_supplier_code']."', 
            '".$data['product_code']."', 
            '".$data['stock_group_code']."', 
            '".static::$db->real_escape_string($data['invoice_supplier_list_product_name'])."', 
            '".static::$db->real_escape_string($data['invoice_supplier_list_product_detail'])."', 
            '".$data['invoice_supplier_list_no']."', 
            '".$data['invoice_supplier_list_qty']."', 
            '".$data['invoice_supplier_list_price']."', 
            '".$data['invoice_supplier_list_total']."', 
            '".$data['invoice_supplier_list_freight']."', 
            '".$data['invoice_supplier_list_freight_total']."',
            '".$data['invoice_supplier_list_cost']."', 
            '".$data['invoice_supplier_list_cost_total']."', 
            '".static::$db->real_escape_string($data['invoice_supplier_list_remark'])."',
            '".$data['purchase_order_list_code']."', 
            '".$data['addby']."', 
            NOW()
        )";
        
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) { 
            return true;
        }else {
            return false;
        }
    }

    function updateInvoiceSupplierListByCode($data,$code){
        $sql = " UPDATE tb_invoice_supplier_list SET
        product_code = '".$data['product_code']."', 
        stock_group_code = '".$data['stock_group_code']."', 
        invoice_supplier_list_product_name = '".static::$db->real_escape_string($data['invoice_supplier_list_product_name'])."',  
        invoice_supplier_list_product_detail = '".static::$db->real_escape_string($data['invoice_supplier_list_product_detail'])."', 
        invoice_supplier_list_no = '".$data['invoice_supplier_list_no']."',  
        invoice_supplier_list_qty = '".$data['invoice_supplier_list_qty']."', 
        invoice_supplier_list_price = '".$data['invoice_supplier_list_price']."', 
        invoice_supplier_list_total = '".$data['invoice_supplier_list_total']."', 
        invoice_supplier_list_freight = '".$data['invoice_supplier_list_freight']."', 
        invoice_supplier_list_freight_total = '".$data['invoice_supplier_list_freight_total']."', 
        invoice_supplier_list_remark = '".static::$db->real_escape_string($data['invoice_supplier_list_remark'])."', 
        invoice_supplier_list_cost = '".$data['invoice_supplier_list_cost']."', 
        invoice_supplier_list_cost_total = '".$data['invoice_supplier_list_cost_total']."', 
        purchase_order_list_code = '".$data['purchase_order_list_code']."' 
        WHERE invoice_supplier_list_code = '$code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) { 
            return true;
        }else {
            return false;
        }
    }

    function updateCostListByCode($data,$code){
        $sql = " UPDATE tb_invoice_supplier_list SET 
        invoice_supplier_list_price = '".$data['invoice_supplier_list_price']."',
        invoice_supplier_list_total = '".$data['invoice_supplier_list_total']."', 
        invoice_supplier_list_freight = '".$data['invoice_supplier_list_freight']."', 
        invoice_supplier_list_freight_total = '".$data['invoice_supplier_list_freight_total']."', 
        invoice_supplier_list_cost = '".$data['invoice_supplier_list_cost']."', 
        invoice_supplier_list_cost_total = '".$data['invoice_supplier_list_cost_total']."',
        invoice_supplier_list_fix_type = '".$data['invoice_supplier_list_fix_type']."'
        WHERE invoice_supplier_list_code = '$code' 
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function deleteInvoiceSupplierListByCode($code){
        $sql = "DELETE FROM tb_invoice_supplier_list WHERE invoice_supplier_list_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }

    function deleteInvoiceSupplierListByInvoiceSupplierCode($code){
        $sql = "DELETE FROM tb_invoice_supplier_list WHERE invoice_supplier_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteInvoiceSupplierListByInvoiceSupplierCodeNotIN($code,$data){
        $str="'".$data."'";
        if(is_array($data) && count($data) > 0){ 
            $str ="";
            for($i=0; $i<count($data); $i++){
                $str .= "'".$data[$i]."'";
                if($i + 1 < count($data)){
                    $str .= ",";
                }
            }
        }

        $sql = "DELETE FROM tb_invoice_supplier_list WHERE invoice_supplier_code = '$code' AND invoice_supplier_list_code NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
}
?>