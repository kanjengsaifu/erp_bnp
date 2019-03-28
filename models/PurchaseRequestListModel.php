<?php
require_once("BaseModel.php");

class PurchaseRequestListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getPurchaseRequestListBy($code){
        $sql = " SELECT tb_purchase_request_list.product_code, list_qty, stock_group_code, list_remark, supplier_code,
        product_name, product_description, purchase_request_list_code
        FROM tb_purchase_request_list 
        LEFT JOIN tb_product ON tb_purchase_request_list.product_code = tb_product.product_code 
        WHERE purchase_request_code = '$code' 
        ORDER BY list_no, purchase_request_list_code 
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

    function insertPurchaseRequestList($data = []){
        $data['list_remark']=mysqli_real_escape_string(static::$db,$data['list_remark']);

        $sql = " INSERT INTO tb_purchase_request_list ( 
            purchase_request_list_code,
            purchase_request_code,
            product_code,
            supplier_code,
            stock_group_code,
            list_no,
            list_qty,
            list_remark,
            addby,
            adddate
        ) VALUES ( 
            '".$data['purchase_request_list_code']."', 
            '".$data['purchase_request_code']."', 
            '".$data['product_code']."', 
            '".$data['supplier_code']."', 
            '".$data['stock_group_code']."', 
            '".$data['list_no']."', 
            '".$data['list_qty']."', 
            '".$data['list_remark']."',
            '".$data['addby']."', 
            NOW()
        )";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function updatePurchaseRquestListByCode($data,$code){
        $data['list_remark']=mysqli_real_escape_string(static::$db,$data['list_remark']);

        $sql = " UPDATE tb_purchase_request_list SET 
        product_code = '".$data['product_code']."', 
        supplier_code = '".$data['supplier_code']."', 
        stock_group_code = '".$data['stock_group_code']."',
        list_no = '".$data['list_no']."',
        list_qty = '".$data['list_qty']."',
        list_remark = '".$data['list_remark']."' 
        WHERE purchase_request_list_code = '$code'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }

    function updatePurchaseOrderCode($purchase_request_list_code,$purchase_order_list_code){
        $sql = " UPDATE tb_purchase_request_list 
            SET purchase_order_list_code = '$purchase_order_list_code' 
            WHERE purchase_request_list_code = '$purchase_request_list_code' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function deletePurchaseRequestListByCode($code){
        $sql = "DELETE FROM tb_purchase_request_list WHERE purchase_request_list_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }

    function deletePurchaseRequestListByPurchaseRequestCode($code){
        $sql = "DELETE FROM tb_purchase_request_list WHERE purchase_request_code = '$code' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }

    function deletePurchaseRequestListByPurchaseRequestCodeNotIN($code,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                $str .= " '".$data[$i]."' ";
                if($i + 1 < count($data)){
                    $str .= ",";
                }
            }
        }else if ($data != ''){
            $str = $data;
        }

        $sql = "DELETE FROM tb_purchase_request_list WHERE purchase_request_code = '$code' AND purchase_request_list_code NOT IN ($str) ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }
    }
}
?>