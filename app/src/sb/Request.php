<?php

namespace SB;

class Request{

    public static function method(){
        return $_SERVER['REQUEST_METHOD'];
      }

public static function put($name = false){
        $method = self::method();
         if($method == "PUT"){
            if(!$name){
                parse_str(file_get_contents("php://input"),$post_vars);
                return $post_vars;
            }else{
                return isset($_GET[$name]) ? $_GET[$name] : null;
            }
        }

        return null;
    
    }


    public static function get($name = false){
        $method = self::method();
       if($method == "GET"){
            if(!$name){
                return $_GET;
            }else{
                return isset($_GET[$name]) ? $_GET[$name] : null;
            }
        }

        return null;
    
    }

    public static function post($name = false){
        $method = self::method();
        if($method == "POST"){
            if(!$name){
                return $_POST;
            }else{
                return isset($_POST[$name]) ? $_POST[$name] : null;
            }
        }

        return null;
    
    }


     public static function delete($name = false){
        $method = self::method();
        if($method == "DELETE"){
            if(!$name){
                return $_GET;
            }else{
                return isset($_GET[$name]) ? $_GET[$name] : null;
            }
        }

        return null;
    
    }



   public static function input($name = false){
        $method = self::method();
        if($method == "POST"){
            if(!$name){
                return $_POST;
            }else{
                return isset($_POST[$name]) ? $_POST[$name] : null;
            }
        }else if($method == "GET"){
            if(!$name){
                return $_GET;
            }else{
                return isset($_GET[$name]) ? $_GET[$name] : null;
            }
        }

        return null;
    
    }

    public static function file($name = false){
          $method = $_SERVER['REQUEST_METHOD'];
        if($method == "POST"){
            if(!$name){
                return $_FILES;
            }else{
                return $_FILES[$name];
            }
        }
        return null;
    }



    public static function uri(){
        $uri = isset($_GET['uri'])? $_GET['uri'] : '/';
        return  rtrim($uri,'/');
    }

    public static function getCurl($url){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15); 
        curl_setopt($ch, CURLOPT_HEADER, 0);
        
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }


    public static function postCurl($url,$data){
        $params = '';
    foreach($data as $key=>$value):
                $params .= $key.'='.$value.'&';
    endforeach;            
         
    $params = rtrim($params, '&');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15); 
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, count($data)); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
  
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}


