<?php

 /**
 * ====================================================================
 * @Create your custom helper functions here
 * ====================================================================
 */


function getDays($date_1 , $date_2 = null, $differenceFormat = '%a' )
{
    if($date_2 == null){
        $datetime1 = date_create(date('Y-m-d'));
        $datetime2 = date_create($date_1);
    }else{
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);
    }

    $interval = date_diff($datetime1, $datetime2);
    
    return  $interval->format($differenceFormat);

   
    
}


function dateFormat($date = null){
  if(!$date) 
    $date = date('Y-m-d'); 
   
    return date('F d, Y',strtotime($date));
}

function checkVar($var){
    return isset($var) ? $var : null ;
}



function cutStr($str,$size = null){
    if($size != null){
        $count = strlen($str);
        if($count > $size){
            $st = substr($str, 0, strpos($str, ' ', $size));
            if($st == ""){
                return $str;   
            }
        }else{
            return $str;    
        }

        
       
    }   
    return $str;    
       
    
}



function multiArr($arr,$break=3){
    $flag = 0;
    $multiArr = [];
    $length = count($arr);

    if($length > $break){

        foreach ($arr as $key => $value) {
          $tempArr[] = $value;
          $flag++;
          if($flag == $break){
                $flag = 0;
                $multiArr[] =   $tempArr;
                $tempArr = [];
            }
        }

        if(!empty($tempArr)){
             $multiArr[] =   $tempArr;
        }
        return $multiArr;
    }else{
        return $arr;
    }
}

