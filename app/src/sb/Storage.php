<?php 

namespace SB;

class Storage{
    public static function  upload($files,$file_name = null,$target_dir = 'public/uploads/'){
        if(empty($files))
                    return null;
        
        $name = basename($files["name"]);

        if($file_name == null)
            $file_name = basename($files["name"]);
        else{
            $ext = strtolower(pathinfo($name,PATHINFO_EXTENSION));
            $file_name  =  $file_name.'.'.$ext;
           

        }
        $target_file = $target_dir .$file_name;

        $uploadOk = 1;

        
        
            $check = getimagesize($files["tmp_name"]);
            if($check !== false) {
                if(!is_dir($target_dir)){
                    mkdir($target_dir);
                }

                if (move_uploaded_file($files["tmp_name"], $target_file)) {
                    return $target_file;
                } 
               
            } 
        

    return null;
    }
}