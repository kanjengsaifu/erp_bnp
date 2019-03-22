<?php
require_once("BaseModel.php");

class StockIssueListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getStockIssueListBy($stock_issue_code){
        $sql = " SELECT tb_stock_issue_list.product_code, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        stock_issue_list_code, 
        stock_issue_list_qty,
        stock_issue_list_price,
        stock_issue_list_total,
        stock_issue_list_remark 
        FROM tb_stock_issue_list LEFT JOIN tb_product ON tb_stock_issue_list.product_code = tb_product.product_code 
        WHERE stock_issue_code = '$stock_issue_code' 
        ORDER BY stock_issue_list_code 
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


    function insertStockIssueList($data = []){
        $sql = " INSERT INTO tb_stock_issue_list (
            stock_issue_code,
            product_code,
            stock_issue_list_qty, 
            stock_issue_list_price,
            stock_issue_list_total,
            stock_issue_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['stock_issue_code']."', 
            '".$data['product_code']."', 
            '".$data['stock_issue_list_qty']."', 
            '".$data['stock_issue_list_price']."', 
            '".$data['stock_issue_list_total']."', 
            '".$data['stock_issue_list_remark']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {

            $id = mysqli_insert_code(static::$db);

            $sql = "
                CALL insert_stock_issue('".
                $data['stock_group_code']."','".
                $id."','".
                $data['product_code']."','".
                $data['stock_issue_list_qty']."','".
                $data['stock_date']."');
            ";

            //echo $sql . "<br><br>";

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

            return $id; 
        }else {
            return 0;
        }

    }

    

    function updateStockIssueListById($data,$id){

        $sql = " UPDATE tb_stock_issue_list 
            SET product_code = '".$data['product_code']."', 
            stock_issue_code = '".$data['stock_issue_code']."',  
            stock_issue_list_qty = '".$data['stock_issue_list_qty']."',  
            stock_issue_list_price = '".$data['stock_issue_list_price']."', 
            stock_issue_list_total = '".$data['stock_issue_list_total']."',
            stock_issue_list_remark = '".$data['stock_issue_list_remark']."' 
            WHERE stock_issue_list_code = '$id' 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $sql = "
                CALL update_stock_issue('".
                $data['stock_group_code']."','".
                $id."','".
                $data['product_code']."','".
                $data['stock_issue_list_qty']."','".
                $data['stock_date']."');
            ";

            //echo $sql . "<br><br>";

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

           return true;
        }else {
            return false;
        }
    }




    function deleteStockIssueListByCode($id){
        $sql = "DELETE FROM tb_stock_issue_list WHERE stock_issue_list_code = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteStockIssueListByStockIssueID($id){


        $sql = "DELETE FROM tb_stock_issue_list WHERE stock_issue_code = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteStockIssueListByStockIssueIDNotIN($id,$data){
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

        $sql = "    SELECT stock_issue_list_code, stock_group_code 
                    FROM  tb_stock_issue 
                    LEFT JOIN tb_stock_issue_list ON tb_stock_issue.stock_issue_code = tb_stock_issue_list.stock_issue_code
                    WHERE tb_stock_issue_list.stock_issue_code = '$id' 
                    AND stock_issue_list_code NOT IN ($str) ";   

        $sql_delete=[];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $sql_delete [] = "
                    CALL delete_stock_issue('".
                    $row['stock_group_code']."','".
                    $row['stock_issue_list_code']."');
                ";
               
            }
            $result->close();
        }

        for($i = 0 ; $i < count($sql_delete); $i++){
            mysqli_query(static::$db,$sql_delete[$i], MYSQLI_USE_RESULT);
        }





        $sql = "DELETE FROM tb_stock_issue_list WHERE stock_issue_code = '$id' AND stock_issue_list_code NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>