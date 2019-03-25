<?php
require_once("BaseModel.php"); 

class StockMoveListModel extends BaseModel{ 
    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        } 
    }

    function getStockMoveListBy($stock_move_code){
        $sql = " SELECT tb_stock_move_list.product_code, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        stock_move_list_code, 
        stock_move_list_qty,
        stock_move_list_remark 
        FROM tb_stock_move_list LEFT JOIN tb_product ON tb_stock_move_list.product_code = tb_product.product_code 
        WHERE stock_move_code = '$stock_move_code' 
        ORDER BY stock_move_list_code 
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


    function insertStockMoveList($data = []){
        $sql = " INSERT INTO tb_stock_move_list (
            stock_move_code,
            product_code,
            stock_move_list_qty, 
            stock_move_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['stock_move_code']."', 
            '".$data['product_code']."', 
            '".$data['stock_move_list_qty']."', 
            '".$data['stock_move_list_remark']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {

            $id = mysqli_insert_code(static::$db);
            return $id; 
        }else {
            return 0;
        }

    }

    

    function updateStockMoveListById($data,$id){

        $sql = " UPDATE tb_stock_move_list 
            SET product_code = '".$data['product_code']."', 
            stock_move_code = '".$data['stock_move_code']."',  
            stock_move_list_qty = '".$data['stock_move_list_qty']."',  
            stock_move_list_remark = '".$data['stock_move_list_remark']."' 
            WHERE stock_move_list_code = '$id' 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
          
           return true;
        }else {
            return false;
        }
    }




    function deleteStockMoveListByCode($id){
        $sql = "DELETE FROM tb_stock_move_list WHERE stock_move_list_code = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteStockMoveListByStockMoveID($id){


        $sql = "DELETE FROM tb_stock_move_list WHERE stock_move_code = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteStockMoveListByStockMoveIDNotIN($id,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                $str .= $data[$i];
                if($i + 1 < count($data)){
                    $str .= ',';
                }
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }
  

        $sql = "DELETE FROM tb_stock_move_list WHERE stock_move_code = '$id' AND stock_move_list_code NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>