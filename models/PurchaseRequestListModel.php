<?php
require_once("BaseModel.php");

class PurchaseRequestListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getPurchaseRequestListBy($purchase_request_code){
        $sql = " SELECT tb_purchase_request_list.product_code, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        product_description,   
        purchase_request_list_code, 
        purchase_request_list_qty, 
        stock_group_code,
        supplier_code,
        purchase_request_list_delivery,
        purchase_request_list_remark 
        FROM tb_purchase_request_list LEFT JOIN tb_product ON tb_purchase_request_list.product_code = tb_product.product_code 
        WHERE purchase_request_code = '$purchase_request_code' 
        ORDER BY purchase_request_list_no, purchase_request_list_code 
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
        $sql = " INSERT INTO tb_purchase_request_list ( 
            purchase_request_code,
            purchase_request_list_no,
            stock_group_code,
            supplier_code,
            product_code,
            purchase_request_list_qty,
            purchase_request_list_delivery,
            purchase_request_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES ( 
            '".$data['purchase_request_code']."', 
            '".$data['purchase_request_list_no']."', 
            '".$data['stock_group_code']."', 
            '".$data['supplier_code']."', 
            '".$data['product_code']."', 
            '".$data['purchase_request_list_qty']."', 
            '".$data['purchase_request_list_delivery']."', 
            '".static::$db->real_escape_string($data['purchase_request_list_remark'])."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        //echo $sql."<br>";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_code(static::$db);
        }else {
            return 0;
        }

    }

    function updatePurchaseRquestListByCode($data,$id){

        $sql = " UPDATE tb_purchase_request_list 
            SET product_code = '".$data['product_code']."', 
            purchase_request_list_no = '".$data['purchase_request_list_no']."',
            stock_group_code = '".$data['stock_group_code']."',
            supplier_code = '".$data['supplier_code']."',
            purchase_request_list_qty = '".$data['purchase_request_list_qty']."',
            purchase_request_list_delivery = '".$data['purchase_request_list_delivery']."', 
            purchase_request_list_remark = '".static::$db->real_escape_string($data['purchase_request_list_remark'])."' 
            WHERE purchase_request_list_code = '$id'
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updatePurchaseOrderId($purchase_request_list_code,$purchase_order_list_code){
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


    function deletePurchaseRequestListByID($id){
        $sql = "DELETE FROM tb_purchase_request_list WHERE purchase_request_list_code = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deletePurchaseRequestListByPurchaseRequestID($id){
        $sql = "DELETE FROM tb_purchase_request_list WHERE purchase_request_code = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deletePurchaseRequestListByPurchaseRequestIDNotIN($id,$data){
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
        }else{
            $str='0';
        }

        $sql = "DELETE FROM tb_purchase_request_list WHERE purchase_request_code = '$id' AND purchase_request_list_code NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>