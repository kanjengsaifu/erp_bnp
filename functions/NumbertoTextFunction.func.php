<?PHP 
class Number2Text{


//แปลงตัวเลข เป็น ข้อความ จุดทศนิยมเป็นแบบสตางค์
    function convert($number){ 
        $txtnum1 = array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ'); 
        $txtnum2 = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน'); 
        $number = str_replace(",","",$number); 
        $number = str_replace(" ","",$number); 
        $number = str_replace("บาท","",$number); 
        $number = explode(".",$number); 

        if(sizeof($number)>2){ 
            return 'ทศนิยมหลายตัวนะจ๊ะ'; 
            exit; 
        } 

        $strlen = strlen($number[0]); 
        $convert = ''; 
        for($i=0;$i<$strlen;$i++){ 
            $n = substr($number[0], $i,1); 
            if($n!=0){ 
                if($i==($strlen-1) && $n==1){ $convert .= 'เอ็ด'; } 
                elseif($i==($strlen-2) && $n==2){  $convert .= 'ยี่'; } 
                elseif($i==($strlen-2) && $n==1){ $convert .= ''; } 
                else{ $convert .= $txtnum1[$n]; } 
                $convert .= $txtnum2[$strlen-$i-1]; 
            } 
        } 

        $convert .= 'บาท'; 
        if($number[1]=='0' OR $number[1]=='00' OR 
            $number[1]==''){ 
            $convert .= 'ถ้วน'; 
        }else{ 
            $strlen = strlen($number[1]); 
            for($i=0;$i<$strlen;$i++){ 
                $n = substr($number[1], $i,1); 
                if($n!=0){ 
                    if($i==($strlen-1) && $n==1){$convert .= 'เอ็ด';} 
                    elseif($i==($strlen-2) && $n==2){$convert .= 'ยี่';} 
                    elseif($i==($strlen-2) && $n==1){$convert .= '';} 
                    else{ $convert .= $txtnum1[$n];} 
                    $convert .= $txtnum2[$strlen-$i-1]; 
                } 
            } 
            $convert .= 'สตางค์'; 
        } 
        return $convert; 
    } 
    

    
 
//แปลงตัวเลข เป็น ข้อความ จุดทศนิยมแบบตัวเลข
    function num2wordsThai($num){
        $num=str_replace(",","",$num);
        $num_decimal=explode(".",$num);
        $num=$num_decimal[0];
        $returnNumWord="";
        $lenNumber = strlen($num);
        $lenNumber2=$lenNumber-1;
        $kaGroup=array("","สิบ","ร้อย","พัน","หมื่น","แสน","ล้าน","สิบ","ร้อย","พัน","หมื่น","แสน","ล้าน");
        $kaDigit=array("","หนึ่ง","สอง","สาม","สี่","ห้า","หก","เจ็ต","แปด","เก้า");
        $kaDigitDecimal=array("ศูนย์","หนึ่ง","สอง","สาม","สี่","ห้า","หก","เจ็ต","แปด","เก้า");
        $ii=0;
        for($i=$lenNumber2;$i>=0;$i--){
            $kaNumWord[$i]=substr($num,$ii,1);
            $ii++;
        }
        $ii=0;
        for($i=$lenNumber2;$i>=0;$i--){
            if(($kaNumWord[$i]==2 && $i==1) || ($kaNumWord[$i]==2 && $i==7)){
            $kaDigit[$kaNumWord[$i]]="ยี่";
            }else{
                if($kaNumWord[$i]==2){
                    $kaDigit[$kaNumWord[$i]]="สอง";
                }
                if(($kaNumWord[$i]==1 && $i<=2 && $i==0) || ($kaNumWord[$i]==1 && $lenNumber>6 && $i==6)){
                    if($kaNumWord[$i+1]==0){
                        $kaDigit[$kaNumWord[$i]]="หนึ่ง";
                    }else{
                        $kaDigit[$kaNumWord[$i]]="เอ็ด";
                    }
                }elseif(($kaNumWord[$i]==1 && $i<=2 && $i==1) || ($kaNumWord[$i]==1 && $lenNumber>6 && $i==7)){
                    $kaDigit[$kaNumWord[$i]]="";
                }else{
                    if($kaNumWord[$i]==1){
                        $kaDigit[$kaNumWord[$i]]="หนึ่ง";
                    }
                }
            }
            if($kaNumWord[$i]==0){
                if($i!=6){
                    $kaGroup[$i]="";
                }
            }
            $kaNumWord[$i]=substr($num,$ii,1);
            $ii++;
            $returnNumWord.=$kaDigit[$kaNumWord[$i]].$kaGroup[$i];
        }
        if(isset($num_decimal[1])){
            $returnNumWord.="จุด";
            for($i=0;$i<strlen($num_decimal[1]);$i++){
                $returnNumWord.=$kaDigitDecimal[substr($num_decimal[1],$i,1)];
            }
        }
        return $returnNumWord;
    }
}
?>
