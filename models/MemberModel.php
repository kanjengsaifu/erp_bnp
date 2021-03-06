<?php
 
require_once("BaseModel.php");
class MemberModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }
     
    
 

    function getNewMemberBy($songserm_code,$member_type="",$keyword=""){
        $str_member_type ="";
        $str_keyword ="";
        if($member_type!=""){
            $str_member_type = " AND member_type = '$member_type' "; 
        }
        if($keyword!=""){
            $str_keyword = " AND name LIKE '%$keyword%' "; 
        }
        // echo $date_start;
        
        $sql = " SELECT * 
        FROM  
        ((SELECT tb_contractor.contractor_code AS code,  CONCAT(contractor_prefix,' ',contractor_name,' ',contractor_lastname) as name , CONCAT('contractor') AS member_type ,profile_image , amphur_name , province_name 
        FROM tb_contractor  
        INNER JOIN tb_village ON tb_contractor.village_id = tb_village.village_id 
        INNER JOIN tb_district ON tb_village.district_id = tb_district.district_id 
        INNER JOIN tb_amphur ON tb_district.amphur_id = tb_amphur.amphur_id 
        INNER JOIN tb_province ON tb_amphur.province_id = tb_province.province_id  
        WHERE tb_contractor.addby = '$songserm_code' 
        AND tb_contractor.status_code = '00' 
        ORDER BY CONCAT(tb_contractor.contractor_name,' ',tb_contractor.contractor_lastname) ) 
        union 
        (SELECT tb_dealer.dealer_code AS code,  CONCAT(dealer_prefix,' ',dealer_name,' ',dealer_lastname) as name , CONCAT('dealer') AS member_type ,profile_image  , amphur_name , province_name 
        FROM tb_dealer  
        INNER JOIN tb_village ON tb_dealer.village_id = tb_village.village_id 
        INNER JOIN tb_district ON tb_village.district_id = tb_district.district_id 
        INNER JOIN tb_amphur ON tb_district.amphur_id = tb_amphur.amphur_id 
        INNER JOIN tb_province ON tb_amphur.province_id = tb_province.province_id 
        WHERE tb_dealer.addby = '$songserm_code' 
        AND tb_dealer.status_code = '00' 
        ORDER BY CONCAT(tb_dealer.dealer_name,' ',tb_dealer.dealer_lastname) ) 
        union 
        (SELECT tb_agent.agent_code AS code,  CONCAT(agent_prefix,' ',agent_name,' ',agent_lastname) as name , CONCAT('agent') AS member_type  ,profile_image  , amphur_name , province_name 
        FROM tb_agent  
        INNER JOIN tb_village ON tb_agent.village_id = tb_village.village_id 
        INNER JOIN tb_district ON tb_village.district_id = tb_district.district_id 
        INNER JOIN tb_amphur ON tb_district.amphur_id = tb_amphur.amphur_id 
        INNER JOIN tb_province ON tb_amphur.province_id = tb_province.province_id 
        WHERE tb_agent.addby = '$songserm_code' 
        AND tb_agent.status_code = '00' 
        ORDER BY CONCAT(tb_agent.agent_name,' ',tb_agent.agent_lastname)  )  
        union 
        (SELECT tb_farmer.farmer_code AS code,  CONCAT(farmer_prefix,' ',farmer_name,' ',farmer_lastname) as name , CONCAT('farmer') AS member_type  ,profile_image  , amphur_name , province_name 
        FROM tb_farmer  
        INNER JOIN tb_village ON tb_farmer.village_id = tb_village.village_id 
        INNER JOIN tb_district ON tb_village.district_id = tb_district.district_id 
        INNER JOIN tb_amphur ON tb_district.amphur_id = tb_amphur.amphur_id 
        INNER JOIN tb_province ON tb_amphur.province_id = tb_province.province_id 
        WHERE tb_farmer.addby = '$songserm_code' 
        AND tb_farmer.status_code = '00' 
        ORDER BY CONCAT(tb_farmer.farmer_name,' ',tb_farmer.farmer_lastname) )) AS tb_member   
        WHERE 1   
        $str_member_type 
        $str_keyword
        ";
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }
    function getMemberBy($songserm_code,$member_type="",$keyword=""){
        $str_member_type ="";
        $str_keyword ="";
        if($member_type!=""){
            $str_member_type = " AND member_type = '$member_type' "; 
        }
        if($keyword!=""){
            $str_keyword = " AND name LIKE '%$keyword%' "; 
        }
        // echo $date_start;
        
        $sql = " SELECT * 
        FROM  
        ((SELECT tb_contractor.contractor_code AS code,  CONCAT(contractor_prefix,' ',contractor_name,' ',contractor_lastname) as name , CONCAT('contractor') AS member_type ,profile_image , amphur_name , province_name 
        FROM tb_contractor  
        INNER JOIN tb_village ON tb_contractor.village_id = tb_village.village_id 
        INNER JOIN tb_district ON tb_village.district_id = tb_district.district_id 
        INNER JOIN tb_amphur ON tb_district.amphur_id = tb_amphur.amphur_id 
        INNER JOIN tb_province ON tb_amphur.province_id = tb_province.province_id 
        INNER JOIN tb_zone_contractor ON tb_contractor.contractor_code = tb_zone_contractor.contractor_code 
        INNER JOIN tb_zone_songserm ON tb_zone_contractor.zone_code = tb_zone_songserm.zone_code 
        WHERE tb_zone_songserm.songserm_code = '$songserm_code' 
        ORDER BY CONCAT(tb_contractor.contractor_name,' ',tb_contractor.contractor_lastname) ) 
        union 
        (SELECT tb_dealer.dealer_code AS code,  CONCAT(dealer_prefix,' ',dealer_name,' ',dealer_lastname) as name , CONCAT('dealer') AS member_type ,profile_image  , amphur_name , province_name 
        FROM tb_dealer  
        INNER JOIN tb_village ON tb_dealer.village_id = tb_village.village_id 
        INNER JOIN tb_district ON tb_village.district_id = tb_district.district_id 
        INNER JOIN tb_amphur ON tb_district.amphur_id = tb_amphur.amphur_id 
        INNER JOIN tb_province ON tb_amphur.province_id = tb_province.province_id 
        INNER JOIN tb_zone_list ON tb_dealer.dealer_code = tb_zone_list.dealer_code  
        INNER JOIN tb_zone_songserm ON tb_zone_list.zone_code = tb_zone_songserm.zone_code 
        WHERE tb_zone_songserm.songserm_code = '$songserm_code' 
        ORDER BY CONCAT(tb_dealer.dealer_name,' ',tb_dealer.dealer_lastname) ) 
        union 
        (SELECT tb_agent.agent_code AS code,  CONCAT(agent_prefix,' ',agent_name,' ',agent_lastname) as name , CONCAT('agent') AS member_type  ,profile_image  , amphur_name , province_name 
        FROM tb_agent  
        INNER JOIN tb_village ON tb_agent.village_id = tb_village.village_id 
        INNER JOIN tb_district ON tb_village.district_id = tb_district.district_id 
        INNER JOIN tb_amphur ON tb_district.amphur_id = tb_amphur.amphur_id 
        INNER JOIN tb_province ON tb_amphur.province_id = tb_province.province_id 
        INNER JOIN tb_zone_list ON tb_agent.agent_code = tb_zone_list.agent_code 
        INNER JOIN tb_zone_songserm ON tb_zone_list.zone_code = tb_zone_songserm.zone_code 
        WHERE tb_zone_songserm.songserm_code = '$songserm_code' 
        ORDER BY CONCAT(tb_agent.agent_name,' ',tb_agent.agent_lastname)  )  
        union 
        (SELECT tb_farmer.farmer_code AS code,  CONCAT(farmer_prefix,' ',farmer_name,' ',farmer_lastname) as name , CONCAT('farmer') AS member_type  ,profile_image  , amphur_name , province_name 
        FROM tb_farmer  
        INNER JOIN tb_village ON tb_farmer.village_id = tb_village.village_id 
        INNER JOIN tb_district ON tb_village.district_id = tb_district.district_id 
        INNER JOIN tb_amphur ON tb_district.amphur_id = tb_amphur.amphur_id 
        INNER JOIN tb_province ON tb_amphur.province_id = tb_province.province_id 
        INNER JOIN tb_zone_list ON tb_farmer.village_id = tb_zone_list.village_id 
        INNER JOIN tb_zone_songserm ON tb_zone_list.zone_code = tb_zone_songserm.zone_code 
        WHERE tb_zone_songserm.songserm_code = '$songserm_code' 
        ORDER BY CONCAT(tb_farmer.farmer_name,' ',tb_farmer.farmer_lastname) )) AS tb_member   
        WHERE 1   
        $str_member_type 
        $str_keyword
        ";
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }
}
?>