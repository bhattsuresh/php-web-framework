<?php

namespace SB;

/**
 * ==============================
 * The Base Controller
 * ==============================
 */

class Controller{

        public $view;
        public $key;
        public $val;


        public function view($view_name,$flag = false,$header = null,$footer = null){
           
            $pos = strpos($view_name, ".");
           if($pos){
                $ex = explode('.',$view_name);
                $view_name = implode('/',$ex);
           }
                
            $this->view = new View($view_name,$flag,$header,$footer);
            return $this;

        }

        function with($key ,$val){

            $this->key[] = $key;
            $this->val[] = $val;
            return $this;

        }

        
    
}
