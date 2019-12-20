<?php
namespace SB;

class Api{
  

   public function table($name){
            $db = new DB;
            $db->setApi($name);
            return $db;
   }
    
}