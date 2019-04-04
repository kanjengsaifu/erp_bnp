<?php
require_once("BaseModel.php");

class MaintenanceStockModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    //***************************************************************************************************************** */
    //
    //
    //  Function ใช้ภายในระบบ Summit Porduct, Invoice Supplier, Stock Move
    //
    //
    //***************************************************************************************************************** */

    function runMaintenance($start_date = ''){
        if($start_date == ''){ // 1-1. ถ้าเป็นการซ่อมแซมระบบทั้งหมด 
            $sql = "TRUNCATE TABLE tb_stock";
            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

            // 1-1.2 ล้างประวัติคลังสินค้า
            $sql = "SELECT table_name FROM tb_stock_group ";
            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                $data = [];
                while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close(); 

                for($i = 0; $i < count($data); $i++){
                    $sql = "TRUNCATE TABLE ".$data[$i]['table_name'];
                    mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
                }
            }

            // 1-1.3 ทำการนำสินค้าตั้งต้น เข้าสู่คลังสินค้าต่างๆ 
            $sql = "SELECT * FROM tb_summit_product ORDER BY stock_group_code , product_code";

            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                $data = [];
                while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close(); 

                // 1-1.3.1 วนรอบปรับต้นทุนต่างๆ ในคลังสินค้า
                for($i = 0; $i < count($data); $i++){  
                    $this->addSummitProduct($data[$i]['adddate'], $data[$i]['stock_group_code'], $data[$i]['summit_product_code'], $data[$i]['product_code'], $data[$i]['summit_product_qty'], $data[$i]['summit_product_cost']);
                }
            }

            $str_invoice_supplier = " ";
            $str_move = " ";
            $str_change = " ";
            $str_invoice_customer = " ";
            $str_issue = " ";
        }else{ // 1-2. ถ้าเป็นการซ่อมแซมข้อมูล ณ วันที่นั้นๆ
            // 1-2.1 ล้างข้อมูลทั้งหมดในตาราง รายงานคลังสินค้า
            $sql = "TRUNCATE TABLE tb_stock";
            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

            // 1-2.2 ลบข้อมูลในคลังสินค้าทุกคลังที่มีการเคลื่อนไหวตั้งแต่วันที่ทำการแก้ไข และ ปรับ Index ของทุกคลังสินค้าใหม่ ให้เท่ากับ index ล่าสุด
            $sql = "SELECT * FROM tb_stock_group";
            $data = [];
            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close(); 
                
                for($i = 0; $i < count($data); $i++){
                    //1-2.2.1 ล้างประวัติคลังสินค้า ตั้งแต่วันที่ทำการแก้ไข 
                    $sql = "DELETE FROM ".$data[$i]['table_name']." WHERE stock_date >= STR_TO_DATE('$start_date','%Y-%m-%d') ";
                    mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
                    
                    // 1-2.3 ปรับ Index ของทุกคลังสินค้าใหม่ ให้เท่ากับ index ล่าสุด
                    $sql = "ALTER TABLE ".$data[$i]['table_name']." AUTO_INCREMENT = (SELECT MAX(stock_code) + 1 FROM ".$data[$i]['table_name']." )";
                    mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

                    $sql = "SELECT * FROM ".$data[$i]['table_name']." 
                    WHERE stock_code IN ( 
                        SELECT MAX(stock_code) 
                        FROM ".$data[$i]['table_name']."  
                        GROUP BY product_code 
                    )";

                    if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                        $data_transaction = [];
                        while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                            $data_transaction[] = $row;
                        }
                        $result->close(); 

                        for($i_tran = 0; $i_tran < count($data_transaction); $i_tran++){
                            $sql = "INSERT INTO tb_stock (
                                stock_group_code,
                                product_code,
                                stock_qty,
                                stock_cost_avg
                            ) VALUES ('".
                            $data[$i]['stock_group_code']."','".
                            $data_transaction[$i_tran]['product_code']."','".
                            $data_transaction[$i_tran]['stock_qty']."','".
                            $data_transaction[$i_tran]['stock_cost_avg']."'
                            )"; 
            
                            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
                        }
                    }
                }
            }

            $str_invoice_supplier = " AND invoice_supplier_receive_date >= STR_TO_DATE('$start_date','%Y-%m-%d') ";
            $str_move = " AND stock_move_date >= STR_TO_DATE('$start_date','%Y-%m-%d') ";
            $str_change = " AND stock_change_product_date >= STR_TO_DATE('$start_date','%Y-%m-%d') ";
            $str_invoice_customer = " AND invoice_customer_date >= STR_TO_DATE('$start_date','%Y-%m-%d') ";
            $str_issue = " AND stock_issue_date >= STR_TO_DATE('$start_date','%Y-%m-%d') ";
        }

        //2. ดึงข้อมูลการรับสินค้าเข้าเรียงตามวันที่
        $sql_purchase = "SELECT 
        tb_invoice_supplier.invoice_supplier_code as transaction_code,  
        invoice_supplier_receive_date as stock_date, 
        'stock_purchase' as transaction_type, 
        stock_group_code,   
        '0' as stock_group_code_out, 
        '0' as stock_group_code_in, 
        invoice_supplier_list_code,  
        '0' as stock_move_list_code, 
        '0' as stock_change_product_list_code, 
        '0' as invoice_customer_list_code,   
        '0' as stock_issue_list_code,  
        tb_invoice_supplier_list.product_code as product_code,  
        '0' as product_code_old, 
        '0' as product_code_new, 
        invoice_supplier_list_qty as qty, 
        invoice_supplier_list_cost as cost 
        FROM tb_invoice_supplier 
        LEFT JOIN tb_invoice_supplier_list ON tb_invoice_supplier.invoice_supplier_code = tb_invoice_supplier_list.invoice_supplier_code 
        LEFT JOIN tb_product ON tb_invoice_supplier_list.product_code = tb_product.product_code 
        LEFT JOIN tb_product_category ON tb_product.product_category_code = tb_product_category.product_category_code 
        WHERE invoice_supplier_begin = '0' AND stock_event = '1'  
        AND invoice_supplier_list_code IS NOT NULL 
        $str_invoice_supplier
        GROUP BY invoice_supplier_list_code 
        ORDER BY invoice_supplier_receive_date, tb_invoice_supplier.invoice_supplier_code";

        //ดึงข้อมูลการย้ายคลังสินค้าเข้าเรียงตามวันที่
        $sql_move = "SELECT 
        tb_stock_move.stock_move_code as transaction_code,  
        stock_move_date as stock_date,  
        'stock_move' as transaction_type, 
        '0' as stock_group_code,   
        stock_group_code_out, 
        stock_group_code_in, 
        '0' as invoice_supplier_list_code, 
        stock_move_list_code, 
        '0' as stock_change_product_list_code, 
        '0' as invoice_customer_list_code,   
        '0' as stock_issue_list_code,  
        tb_stock_move_list.product_code as product_code,  
        '0' as product_code_old, 
        '0' as product_code_new, 
        stock_move_list_qty as qty, 
        '0' as cost 
        FROM tb_stock_move 
        LEFT JOIN tb_stock_move_list ON tb_stock_move.stock_move_code = tb_stock_move_list.stock_move_code 
        WHERE stock_move_list_code IS NOT NULL 
        $str_move 
        GROUP BY stock_move_list_code 
        ORDER BY stock_move_date , tb_stock_move.stock_move_code";

        //ดึงข้อมูลการย้ายจำนวนจากสินค้าชื่อนึง ไป ยังสินค้าชื่อนึง เข้าเรียงตามวันที่
        $sql_rename = "SELECT 
        tb_stock_change_product.stock_change_product_code as transaction_code,  
        stock_change_product_date as stock_date,  
        'stock_rename' as transaction_type, 
        '0' as stock_group_code,   
        stock_group_code_old as stock_group_code_out, 
        stock_group_code_new as stock_group_code_in, 
        '0' as invoice_supplier_list_code, 
        '0' as stock_move_list_code, 
        stock_change_product_list_code, 
        '0' as invoice_customer_list_code,   
        '0' as stock_issue_list_code,  
        '0' as product_code, 
        product_code_old, 
        product_code_new, 
        stock_change_product_list_qty as qty, 
        stock_change_product_list_price as cost 
        FROM tb_stock_change_product 
        LEFT JOIN tb_stock_change_product_list ON tb_stock_change_product.stock_change_product_code = tb_stock_change_product_list.stock_change_product_code 
        WHERE stock_change_product_list_code IS NOT NULL 
        $str_change 
        GROUP BY stock_change_product_list_code 
        ORDER BY stock_change_product_date , tb_stock_change_product.stock_change_product_code";

        //ดึงข้อมูลการขายสินค้าเรียงตามวันที่
        $sql_sale = "SELECT 
        tb_invoice_customer.invoice_customer_code as transaction_code,  
        invoice_customer_date as stock_date, 
        'stock_sale' as transaction_type, 
        stock_group_code,  
        '0' as stock_group_code_out, 
        '0' as stock_group_code_in, 
        '0' as invoice_supplier_list_code, 
        '0' as stock_move_list_code, 
        '0' as stock_change_product_list_code, 
        invoice_customer_list_code,   
        '0' as stock_issue_list_code,  
        tb_invoice_customer_list.product_code as product_code, 
        '0' as product_code_old, 
        '0' as product_code_new, 
        invoice_customer_list_qty as qty, 
        invoice_customer_list_price as cost 
        FROM tb_invoice_customer 
        LEFT JOIN tb_invoice_customer_list ON tb_invoice_customer.invoice_customer_code = tb_invoice_customer_list.invoice_customer_code 
        LEFT JOIN tb_product ON tb_invoice_customer_list.product_code = tb_product.product_code 
        LEFT JOIN tb_product_category ON tb_product.product_category_code = tb_product_category.product_category_code 
        WHERE invoice_customer_begin = '0' AND stock_event = '1' AND invoice_customer_close = '0'
        AND invoice_customer_list_code IS NOT NULL  
        $str_invoice_customer 
        GROUP BY invoice_customer_list_code 
        ORDER BY invoice_customer_date, tb_invoice_customer.invoice_customer_code";

        //ดึงข้อมูลการตัดคลังสินค้าเรียงตามวันที่
        $sql_issue = "SELECT 
        tb_stock_issue.stock_issue_code as transaction_code, 
        stock_issue_date as stock_date, 
        '5_issue' as transaction_type, 
        stock_group_code,  
        '0' as stock_group_code_out, 
        '0' as stock_group_code_in, 
        '0' as invoice_supplier_list_code, 
        '0' as stock_move_list_code, 
        '0' as stock_change_product_list_code, 
        '0' as invoice_customer_list_code,   
        stock_issue_list_code,  
        product_code, 
        '0' as product_code_old, 
        '0' as product_code_new, 
        stock_issue_list_qty as qty, 
        '0' as cost 
        FROM tb_stock_issue 
        LEFT JOIN tb_stock_issue_list ON tb_stock_issue.stock_issue_code = tb_stock_issue_list.stock_issue_code 
        WHERE stock_issue_list_code IS NOT NULL 
        $str_issue 
        GROUP BY stock_issue_list_code 
        ORDER BY stock_issue_date, tb_stock_issue.stock_issue_code";

        // ดึงข้อมูล Transaction รวมทั้งหมด
        $sql = "SELECT 
        transaction_code,  
        stock_date, 
        transaction_type, 
        stock_group_code, 
        stock_group_code_out, 
        stock_group_code_in, 
        invoice_supplier_list_code, 
        stock_move_list_code,
        invoice_customer_list_code, 
        stock_change_product_list_code, 
        invoice_customer_list_code, 
        stock_issue_list_code, 
        product_code,  
        product_code_old,  
        product_code_new,  
        qty, 
        cost 
        FROM(
            ($sql_purchase) 
            UNION  ($sql_move) 
            UNION  ($sql_rename) 
            UNION  ($sql_sale) 
        ) as tb_transaction  
        ORDER BY stock_date, transaction_type ASC 
        "; 
        
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();

            for($i = 0; $i < count($data); $i++){
                if($data[$i]['transaction_type'] == 'stock_purchase'){ // คำนวนคลังสินค้าในรูปแบบของการซื้อ
                    $this->addPurchase($data[$i]['stock_date'], $data[$i]['stock_group_code'], $data[$i]['invoice_supplier_list_code'], $data[$i]['product_code'], $data[$i]['qty'], $data[$i]['cost']);
                }else if($data[$i]['transaction_type'] == 'stock_move'){ // คำนวนคลังสินค้าในรูปแบบของการย้ายคลังสินค้า
                    $this->addMoveStock($data[$i]['stock_date'], $data[$i]['stock_group_code_out'],$data[$i]['stock_group_code_in'],$data[$i]['stock_move_list_code'], $data[$i]['product_code'], $data[$i]['qty']);
                }else if($data[$i]['transaction_type'] == 'stock_rename'){ // คำนวนคลังสินค้าในรูปแบบของการย้ายสินค้าไปยังสินค้าชื่ออื่น 
                    $this->addStockChangeProduct($data[$i]['stock_date'],$data[$i]['stock_group_code_out'],$data[$i]['stock_group_code_in'] ,$data[$i]['stock_change_product_list_code'], $data[$i]['product_code_old'], $data[$i]['product_code_new'], $data[$i]['qty']);
                }else if($data[$i]['transaction_type'] == 'stock_sale'){ // คำนวนคลังสินค้าในรูปแบบของการขาย
                    $this->addSaleStock($data[$i]['stock_date'], $data[$i]['stock_group_code'], $data[$i]['invoice_customer_list_code'], $data[$i]['product_code'], $data[$i]['qty']);
                }
            }
        }
    }
    
    //##########################################################################################################
    //
    //####################################### ตรวจสอบและสร้างรายการรายงานคลังสินค้า #################################
    //
    //##########################################################################################################

    function createRowStock($stock_group_code,$product_code){
        $sql = "SELECT COUNT(*) as check_row 
        FROM tb_stock 
        WHERE stock_group_code = '$stock_group_code' 
        AND product_code = '$product_code'
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            if($stock['check_row'] == 0){
                $sql = "INSERT INTO tb_stock (
                    stock_group_code,
                    product_code
                ) VALUES ('".
                $stock_group_code."','".
                $product_code."'
                )"; 

                mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            }
        }
    }

    //##########################################################################################################
    //
    //############################# คำนวนจำนวน และ ต้นทุนสินค้าใหม่เมือเพิ่มรายรับสินค้าเข้า ###############################
    //
    //##########################################################################################################

    function calculatePurchaseCostIn($stock_group_code, $product_code, $qty, $cost){
        $sql = "SELECT stock_qty , stock_cost_avg  
        FROM tb_stock
        WHERE stock_group_code = '$stock_group_code' 
        AND product_code = '$product_code' ;"; 

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();
            
            $stock_qty = $stock['stock_qty'];
            $stock_cost = $stock['stock_cost_avg'];

            $new_qty = $stock_qty + $qty;
            if($new_qty > 0){
                $new_cost = (($stock_qty * $stock_cost) + ($qty * $cost)) / $new_qty;
            }else{
                $new_cost = 0;
            }
 
            $sql = "UPDATE tb_stock SET 
            stock_qty = '$new_qty', 
            stock_cost_avg = '$new_cost' 
            WHERE stock_group_code = '$stock_group_code' 
            AND product_code = '$product_code' 
            "; 

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

            $stock['stock_qty'] = $new_qty;
            $stock['stock_cost_avg'] = $new_cost;
            return $stock;
        }
    }

    //##########################################################################################################
    //
    //############################# คำนวนจำนวน และ ต้นทุนสินค้าใหม่เมือลบรายรับสินค้าเข้า ###############################
    //
    //##########################################################################################################

    function calculatePurchaseCostOut($stock_group_code, $product_code, $qty, $cost){
        $sql = "SELECT stock_qty , stock_cost_avg  
        FROM tb_stock
        WHERE stock_group_code = '$stock_group_code' 
        AND product_code = '$product_code'
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            $stock_qty = $stock['stock_qty'];
            $stock_cost = $stock['stock_cost_avg'];

            $new_qty = $stock_qty - $qty;

            if($new_qty == 0){
                $new_cost = 0;
            }else{
                $new_cost = (($stock_qty * $stock_cost) - ($qty * $cost))/$new_qty;
            }
            
            $sql = "UPDATE tb_stock 
                    SET stock_qty = '$new_qty', 
                        stock_cost_avg = '$new_cost' 
                    WHERE stock_group_code = '$stock_group_code' 
                    AND product_code = '$product_code' ; "; 

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 
            
        }
    }

    //##########################################################################################################
    //
    //############################# คำนวนจำนวน และ ต้นทุนสินค้าใหม่เมือแก้ไขรายรับสินค้าเข้า ###############################
    //
    //##########################################################################################################

    function calculatePurchaseCostUpdate($stock_group_code, $product_code, $qty_old, $cost_old, $qty, $cost){

        $sql = "SELECT stock_qty , stock_cost_avg  
        FROM tb_stock
        WHERE stock_group_code = '$stock_group_code' 
        AND product_code = '$product_code' ;";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            $stock_qty = $stock['stock_qty'];
            $stock_cost = $stock['stock_cost_avg'];

            $new_qty = $stock_qty - $qty_old;
            $new_cost = (($stock_qty * $stock_cost) - ($qty_old * $cost_old))/$new_qty;


            $new_qty = $new_qty + $qty;
            $new_cost = (($stock_qty * $stock_cost) - ($qty * $cost))/$new_qty;

 
            $sql = "UPDATE tb_stock 
                    SET stock_qty = '$new_qty', 
                        stock_cost_avg = '$new_cost' 
                    WHERE stock_group_code = '$stock_group_code' 
                    AND product_code = '$product_code' ; "; 

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 
        }
    }

    //##########################################################################################################
    //
    //############################ คำนวนจำนวน และ ต้นทุนสินค้าใหม่เมือเพิ่มรายการขายสินค้า ###############################
    //
    //##########################################################################################################

    function calculateSaleCostIn($stock_group_code, $product_code, $qty){
        $sql = "SELECT stock_qty , stock_cost_avg  
        FROM tb_stock
        WHERE stock_group_code = '$stock_group_code' 
        AND product_code = '$product_code' ;";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            $stock_qty = $stock['stock_qty'];
            $stock_cost = $stock['stock_cost_avg'];

            $new_qty = $stock_qty - $qty; 
            $new_cost = $stock_cost ;
            $sql = "UPDATE tb_stock 
                    SET stock_qty = '$new_qty', 
                        stock_cost_avg = '$new_cost' 
                    WHERE stock_group_code = '$stock_group_code' 
                    AND product_code = '$product_code' ; "; 

            //echo "<br><br>SQL calculateSaleCostIn : ".$sql;

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

            $stock['stock_qty'] = $new_qty;
            $stock['stock_cost_avg'] = $new_cost; 

            return $stock;
        }else{
            return 0;
        }
    }

    //##########################################################################################################
    //
    //############################ คำนวนจำนวน และ ต้นทุนสินค้าใหม่เมือลบรายการขายสินค้า ###############################
    //
    //##########################################################################################################

    function calculateSaleCostOut($stock_group_code, $product_code, $qty, $cost){
        $sql = "SELECT stock_qty , stock_cost_avg  
        FROM tb_stock
        WHERE stock_group_code = '$stock_group_code' 
        AND product_code = '$product_code' ;";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            $stock_qty = $stock['stock_qty'];
            $stock_cost = $stock['stock_cost_avg'];

            $new_qty = $stock_qty + $qty;
            if($new_qty == 0){
                $new_cost = 0 ;
            }else{
                $new_cost = (($stock_qty * $stock_cost) + ($qty * $cost))/$new_qty;
            }
            
            $sql = "UPDATE tb_stock 
                    SET stock_qty = '$new_qty', 
                        stock_cost_avg = '$new_cost' 
                    WHERE stock_group_code = '$stock_group_code' 
                    AND product_code = '$product_code' ; "; 

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 
        }
    }





    //##########################################################################################################
    //
    //############################# คำนวนจำนวน และ ต้นทุนสินค้าใหม่เมือแก้ไขรายรับสินค้าเข้า ###############################
    //
    //##########################################################################################################

    function calculateSaleCostUpdate($stock_group_code, $product_code, $qty_old, $cost_old, $qty){

        $sql = "SELECT stock_qty , stock_cost_avg  
        FROM tb_stock
        WHERE stock_group_code = '$stock_group_code' 
        AND product_code = '$product_code' ;";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            $stock_qty = $stock['stock_qty'];
            $stock_cost = $stock['stock_cost_avg'];

            $new_qty = $stock_qty + $qty_old;
            $new_cost = (($stock_qty * $stock_cost) + ($qty_old * $cost_old))/$new_qty;


            $new_qty = $new_qty - $qty; 

 
            $sql = "UPDATE tb_stock 
                    SET stock_qty = '$new_qty', 
                        stock_cost_avg = '$new_cost' 
                    WHERE stock_group_code = '$stock_group_code' 
                    AND product_code = '$product_code' ; "; 

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 
            return $new_cost;
        }else{
            return 0;
        }
    }

    //##########################################################################################################
    //
    //##################################### ดึงข้อมูลคลังสินค้าจาก stock_group_code ###################################
    //
    //##########################################################################################################

    function getStockGroupTable($stock_group_code){
        if($stock_group_code != ''){
            $sql = "WHERE stock_group_code = '$stock_group_code' ";
        }else{
            $str = "WHERE stock_group_primary = '1' ";
        }

        $sql ="SELECT table_name,stock_group_code  
        FROM tb_stock_group 
        $str ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }
    }

    //##########################################################################################################
    //
    //################################## ทำการคำณวนต้นทุนเมื่อเพิ่มรายการสินค้ายกยอดมา ################################
    //
    //##########################################################################################################

    function addSummitProduct($stock_date, $stock_group_code = 0, $summit_product_code, $product_code, $qty, $cost){

        $stock_group = $this->getStockGroupTable($stock_group_code);

        $this->createRowStock($stock_group['stock_group_code'],$product_code);
        $stock = $this->calculatePurchaseCostIn($stock_group['stock_group_code'], $product_code, $qty, $cost);
 
        $sql = "INSERT INTO ".$stock_group['table_name']." (
            stock_type,
            product_code, 
            stock_date,
            in_qty, 
            in_cost_avg,
            in_cost_avg_total,
            out_qty, 
            out_cost_avg,
            out_cost_avg_total,
            stock_qty, 
            stock_cost_avg,
            stock_cost_avg_total,
            summit_product_code
        ) VALUE ('".
        "in"."','".
        $product_code."','".
        $stock_date."','".
        $qty."','".
        $cost."','".
        ($qty * $cost)."','".
        (0)."','".
        (0)."','".
        (0)."','".
        ($stock['stock_qty'])."','".
        ($stock['stock_cost_avg'])."','".
        ($stock['stock_qty'] * $stock['stock_cost_avg'])."','".
        $summit_product_code."'
        )"; 

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }

    //##########################################################################################################
    //
    //################################## ทำการคำณวนต้นทุนเมื่อลบรายการสินค้ายกยอดมา ################################
    //
    //##########################################################################################################

    function removeSummitProduct($stock_group_code, $summit_product_code, $product_code, $qty, $cost){
        $stock_group = $this->getStockGroupTable($stock_group_code); 

        $this->createRowStock($stock_group['stock_group_code'],$product_code);
        $stock = $this->calculatePurchaseCostOut($stock_group['stock_group_code'], $product_code, $qty, $cost);

        $sql = "DELETE FROM ".$stock_group['table_name']." WHERE summit_product_code ='".$summit_product_code."'";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 
    }  

    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการรับสินค้า #################################
    //
    //##########################################################################################################

    function addPurchase($stock_date, $stock_group_code , $invoice_supplier_list_code, $product_code, $qty, $cost){

        $stock_group = $this->getStockGroupTable($stock_group_code);

        $this->createRowStock($stock_group['stock_group_code'],$product_code);
        $stock = $this->calculatePurchaseCostIn($stock_group['stock_group_code'], $product_code, $qty, $cost);
 
        $sql = "INSERT INTO ".$stock_group['table_name']." (
            stock_type,
            product_code, 
            stock_date,
            in_qty, 
            in_cost_avg,
            in_cost_avg_total,
            out_qty, 
            out_cost_avg,
            out_cost_avg_total,
            stock_qty, 
            stock_cost_avg,
            stock_cost_avg_total,
            invoice_supplier_list_code
            ) VALUE ('".
        "in"."','".
        $product_code."','".
        $stock_date."','".
        $qty."','".
        $cost."','".
        ($qty * $cost)."','".
        (0)."','".
        (0)."','".
        (0)."','".
        ($stock['stock_qty'])."','".
        ($stock['stock_cost_avg'])."','".
        ($stock['stock_qty'] * $stock['stock_cost_avg'])."','".
        $invoice_supplier_list_code."'); "; 

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }   

    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการขายสินค้า #################################
    //
    //##########################################################################################################

    function addSaleStock($stock_date, $stock_group_code, $invoice_customer_list_code, $product_code, $qty){ 
        $stock_group = $this->getStockGroupTable($stock_group_code); 
        $this->createRowStock($stock_group['stock_group_code'],$product_code);
        $stock = $this->calculateSaleCostIn($stock_group['stock_group_code'],$product_code,$qty); 
 
        $sql = "INSERT INTO ".$stock_group['table_name']." (
            stock_type,
            product_code, 
            stock_date,
            in_qty, 
            in_cost_avg,
            in_cost_avg_total,
            out_qty, 
            out_cost_avg,
            out_cost_avg_total,
            stock_qty, 
            stock_cost_avg,
            stock_cost_avg_total,
            invoice_customer_list_code
            ) VALUE ('".
        "out"."','".
        $product_code."','".
        $stock_date."','".
        (0)."','".
        (0)."','".
        (0)."','".
        $qty."','".
        $stock['stock_cost_avg']."','".
        ($qty * $stock['stock_cost_avg'])."','".
        ($stock['stock_qty'])."','".
        ($stock['stock_cost_avg'])."','".
        ($stock['stock_qty'] * $stock['stock_cost_avg'])."','".
        $invoice_customer_list_code."'); "; 

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    } 

    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อลบรายการขายสินค้า #################################
    //
    //##########################################################################################################

    function removeSaleStock($stock_group_code,$invoice_customer_list_code, $product_code, $qty, $cost){
        $stock = $this->getStockGroupTable($stock_group_code); 

        $this->createRowStock($stock['stock_group_code'],$product_code);
        $stock = $this->calculateSaleCostOut($stock['stock_group_code'], $product_code, $qty, $cost);

        $sql = "DELETE FROM ".$stock['table_name']." WHERE invoice_customer_list_code ='".$invoice_customer_list_code."'";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);  
    }

    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการย้ายสินค้า #################################
    //
    //##########################################################################################################

    function addMoveStock($stock_date,$stock_group_code_out,$stock_group_code_in,$stock_move_list_code, $product_code, $qty){


        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาออก -------------------------------------------
        $stock_out = $this->getStockGroupTable($stock_group_code_out);

        $this->createRowStock($stock_out['stock_group_code'],$product_code);

        $stock = $this->calculateSaleCostIn($stock_out['stock_group_code'],$product_code,$qty); 
 
        $sql = "INSERT INTO ". $stock_out['table_name'] ." (
            stock_type,
            product_code, 
            stock_date,
            in_qty, 
            in_cost_avg,
            in_cost_avg_total,
            out_qty, 
            out_cost_avg,
            out_cost_avg_total,
            stock_qty, 
            stock_cost_avg,
            stock_cost_avg_total,
            stock_move_list_code
            ) VALUE ('".
        "out"."','".
        $product_code."','".
        $stock_date."','".
        (0)."','".
        (0)."','".
        (0)."','".
        $qty."','".
        $stock['stock_cost_avg']."','".
        ($qty * $stock['stock_cost_avg'])."','".
        ($stock['stock_qty'])."','".
        ($stock['stock_cost_avg'])."','".
        ($stock['stock_qty'] * $stock['stock_cost_avg'])."','".
        $stock_move_list_code."'); "; 

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาเข้า -------------------------------------------
        $cost = $stock['stock_cost_avg'];
        $stock_in = $this->getStockGroupTable($stock_group_code_in);
        $this->createRowStock($stock_in['stock_group_code'],$product_code);
        $stock = $this->calculatePurchaseCostIn($stock_in['stock_group_code'], $product_code, $qty, $cost);
 
        $sql = "INSERT INTO ". $stock_in['table_name'] ." (
            stock_type,
            product_code, 
            stock_date,
            in_qty, 
            in_cost_avg,
            in_cost_avg_total,
            out_qty, 
            out_cost_avg,
            out_cost_avg_total,
            stock_qty, 
            stock_cost_avg,
            stock_cost_avg_total,
            stock_move_list_code
            ) VALUE ('".
        "in"."','".
        $product_code."','".
        $stock_date."','".
        $qty."','".
        $cost."','".
        ($qty * $cost)."','".
        (0)."','".
        (0)."','".
        (0)."','".
        ($stock['stock_qty'])."','".
        ($stock['stock_cost_avg'])."','".
        ($stock['stock_qty'] * $stock['stock_cost_avg'])."','".
        $stock_move_list_code."'); "; 

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    } 

    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการย้ายสินค้า #################################
    //
    //##########################################################################################################

    function removeMoveStock($stock_group_code_out,$stock_group_code_in,$stock_move_list_code, $product_code, $qty, $cost){
        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาออก -------------------------------------------
        $stock_out = $this->getStockGroupTable($stock_group_code_out); 

        $this->createRowStock($stock_out['stock_group_code'],$product_code);
        $stock = $this->calculateSaleCostOut($stock['stock_group_code'], $product_code, $qty, $cost);

        $sql = "DELETE FROM ".$stock_out['table_name']." WHERE stock_move_list_code ='".$stock_move_list_code."'";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาเข้า -------------------------------------------
        $stock_in = $this->getStockGroupTable($stock_group_code_in); 

        $this->createRowStock($stock_in['stock_group_code'],$product_code);
        $stock = $this->calculatePurchaseCostOut($stock_in['stock_group_code'], $product_code, $qty, $cost);

        $sql = "DELETE FROM ".$stock_in['table_name']." WHERE stock_move_list_code ='".$stock_move_list_code."'";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);  
    }

    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการย้ายสินค้า #################################
    //
    //##########################################################################################################

    function addStockChangeProduct($stock_date,$stock_group_code_out,$stock_group_code_in ,$stock_change_product_list_code, $product_code_old, $product_code_new, $qty){

        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาออก -------------------------------------------
        $stock_out = $this->getStockGroupTable($stock_group_code_out);

        $this->createRowStock($stock_out['stock_group_code'],$product_code_old);

        $stock = $this->calculateSaleCostIn($stock_out['stock_group_code'],$product_code_old,$qty); 
 
        $sql = "INSERT INTO ". $stock_out['table_name'] ." (
            stock_type,
            product_code, 
            stock_date,
            in_qty, 
            in_cost_avg,
            in_cost_avg_total,
            out_qty, 
            out_cost_avg,
            out_cost_avg_total,
            stock_qty, 
            stock_cost_avg,
            stock_cost_avg_total,
            stock_change_product_list_code
            ) VALUE ('".
        "out"."','".
        $product_code_old."','".
        $stock_date."','".
        (0)."','".
        (0)."','".
        (0)."','".
        $qty."','".
        $stock['stock_cost_avg']."','".
        ($qty * $stock['stock_cost_avg'])."','".
        ($stock['stock_qty'])."','".
        ($stock['stock_cost_avg'])."','".
        ($stock['stock_qty'] * $stock['stock_cost_avg'])."','".
        $stock_change_product_list_code."'); "; 

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาเข้า -------------------------------------------
        $cost = $stock['stock_cost_avg'];
        $stock_in = $this->getStockGroupTable($stock_group_code_in);
        $this->createRowStock($stock_in['stock_group_code'],$product_code_new);

        $stock = $this->calculatePurchaseCostIn($stock_in['stock_group_code'], $product_code_new, $qty, $cost);
 
        $sql = "INSERT INTO ". $stock_in['table_name'] ." (
            stock_type,
            product_code, 
            stock_date,
            in_qty, 
            in_cost_avg,
            in_cost_avg_total,
            out_qty, 
            out_cost_avg,
            out_cost_avg_total,
            stock_qty, 
            stock_cost_avg,
            stock_cost_avg_total,
            stock_change_product_list_code
            ) VALUE ('".
        "in"."','".
        $product_code_new."','".
        $stock_date."','".
        $qty."','".
        $cost."','".
        ($qty * $cost)."','".
        (0)."','".
        (0)."','".
        (0)."','".
        ($stock['stock_qty'])."','".
        ($stock['stock_cost_avg'])."','".
        ($stock['stock_qty'] * $stock['stock_cost_avg'])."','".
        $stock_change_product_list_code."'); "; 

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    } 

    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการย้ายสินค้า #################################
    //
    //##########################################################################################################

    function removeStockChangeProduct($stock_group_code,$stock_change_product_list_code,  $product_code_old, $product_code_new, $qty, $cost){
        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาออก -------------------------------------------
        $stock_out = $this->getStockGroupTable($stock_group_code); 

        $this->createRowStock($stock_out['stock_group_code'],$product_code_old);
        $stock = $this->calculateSaleCostOut($stock['stock_group_code'], $product_code_old, $qty, $cost);

        $sql = "DELETE FROM ".$stock_out['table_name']." WHERE stock_change_product_list_code ='".$stock_change_product_list_code."'";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาเข้า -------------------------------------------
        $stock_in = $this->getStockGroupTable($stock_group_code); 

        $this->createRowStock($stock_in['stock_group_code'],$product_code_new);
        $stock = $this->calculatePurchaseCostOut($stock_in['stock_group_code'], $product_code_new, $qty, $cost);

        $sql = "DELETE FROM ".$stock_in['table_name']." WHERE stock_change_product_list_code ='".$stock_change_product_list_code."'";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);  
    }
}
?>