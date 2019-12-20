<?php
namespace SB;
class Hash {

    function __construct() {
        
    }

    public static function set($data, $cost = 12) {

        $options = ['cost' => $cost];
        return password_hash($data, PASSWORD_BCRYPT, $options);
    }

    public static function get($data, $hash) {

        return password_verify($data, $hash);
    }


    public static function getToken($length = 32){
            $token = "";
            $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

            $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";

            $codeAlphabet.= "0123456789";

            $max = strlen($codeAlphabet); 
       
           for ($i=0; $i < $length; $i++) {
               $token .= $codeAlphabet[random_int(0, $max-1)];
           }
       
           return $token;
       }
    

}
