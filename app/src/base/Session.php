<?php

class Session {
    

    public static function init() {
        session_start();
    }

    public static function put($key, $value) {

        $_SESSION[$key] = $value;
    }
    public static function set($key, $value) {

        $_SESSION[$key] = $value;
    }

    public static function get($key = null,$subkey=null) {

        if (isset($_SESSION[$key]) &&  $subkey == null) {
            return $_SESSION[$key];
        }else if($subkey != null){
            return $_SESSION[$key][$subkey];
        }

        return null;
    }





    public static function all() {

        if (isset($_SESSION)) {
            return $_SESSION;
        }

        return null;
    }

    

    public static function destory() {
        session_destroy();
    }

    public static function forget() {
        session_destroy();
    }

    public static function remove($key){
        if(self::get($key) != null ){
            unset($_SESSION[$key]);
        }
    }

    public static function unset($key){
        if(self::get($key) != null ){
            unset($_SESSION[$key]);
        }
    }

    public static function flush($key, $value = null, $type=null) {
        if($value == null){
                $data = self::get($key);
                if($data != null){
                    self::remove($key);
                }

               return  $data;     
        }else{
            $_SESSION[$key] = $value;
           // $_SESSION['keys'][] = $key;
            if($type != null){
                $_SESSION['status_type'] = $type; 
            }
        }
    }

        
    public static function status(){
       $flush_msg =  self::flush('status');
       $flush_type =  self::flush('status_type');
        return display_flush_message($flush_msg,$flush_type);
    }
        
    

}

